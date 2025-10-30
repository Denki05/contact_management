<?php

namespace App\Imports;

use App\Master\StoreProspek;
use App\Master\CustomerProspek;
use App\Master\Store;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 
use Maatwebsite\Excel\Concerns\WithChunkReading; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class StoreProspekImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $categoryMap;
    private $provinceMap;
    private $cityMap;
    private $failedRows = [];

    public function __construct()
    {
        // Preload data master ke dalam array/collection (Map) untuk menghindari N+1 query
        // Semua nama diubah ke lowercase untuk pengecekan case-insensitive
        
        // Lookup Kategori: Excel 'category_id' (Nama) -> Model ID
        $this->categoryMap = DB::table('master_customer_categories')->pluck('id', 'name')
            ->mapWithKeys(function ($id, $name) {
                // Menggunakan closure tradisional yang didukung PHP 7.3
                return [strtolower($name) => $id];
            });
            
        // Lookup Provinsi: Excel 'province' (Nama) -> Model ID
        $this->provinceMap = DB::table('provinsi')->pluck('prov_id', 'prov_name')
            ->mapWithKeys(function ($id, $name) {
                // Menggunakan closure tradisional yang didukung PHP 7.3
                return [strtolower($name) => $id];
            });

        // Lookup Kabupaten/Kota: Excel 'city' (Nama) -> Model ID
        $this->cityMap = DB::table('kabupaten')->pluck('city_id', 'city_name')
            ->mapWithKeys(function ($id, $name) {
                // Menggunakan closure tradisional yang didukung PHP 7.3
                return [strtolower($name) => $id];
            });
    }
    
    // Normalize company name to standard format: PT. Surya Mandala Abadi
    private function formatCompanyName($name)
    {
        if (!$name) return $name;
    
        $name = trim($name);
    
        // Normalisasi awal: lowercase lalu title case
        $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    
        // Daftar badan hukum yang distandarkan
        $legalWords = ['PT', 'CV', 'UD', 'TBK'];
    
        // Deteksi badan hukum di awal nama
        if (preg_match('/^(Pt|Cv|Ud|Tbk)\.?\s*/i', $name, $match)) {
    
            $entity = strtoupper(str_replace('.', '', $match[1])); // Standardisasi e.g. PT, CV
            $name = preg_replace('/^(Pt|Cv|Ud|Tbk)\.?\s*/i', '', $name); // Hapus prefix lama
    
            $name = trim($name);
            return $entity . '. ' . $name;
        }
    
        return $name; // Jika tidak ada badan hukum → kembalikan nama dalam Title Case
    }


    public function collection(Collection $rows)
    {
        $rowNumber = 1; 

        foreach ($rows as $row) {
            $rowNumber++; 
            
            if ($row->filter()->isEmpty()) {
                continue;
            }

            // --- 1. Ambil Nilai Asli dan Nilai Lowercase untuk Lookup ---
            // Ambil nilai asli dari Excel (untuk disimpan di text_provinsi/text_kota)
            $originalCategoryName = trim($row['category_id'] ?? ''); 
            $originalProvinceName = trim($row['province'] ?? '');
            $originalCityName = trim($row['city'] ?? '');
            
            // Konversi ke lowercase hanya untuk proses lookup ID master data
            $categoryNameLower = strtolower($originalCategoryName);
            $provinceNameLower = strtolower($originalProvinceName);
            $cityNameLower = strtolower($originalCityName);
            
            // Lakukan lookup menggunakan nilai lowercase
            $categoryId = $this->categoryMap->get($categoryNameLower);
            $provinceId = $this->provinceMap->get($provinceNameLower);
            $cityId = $this->cityMap->get($cityNameLower);
            
            $errors = [];
            // Validasi wajib isi
            if (empty($row['name'])) $errors[] = "Nama Store wajib diisi.";
            
            // Validasi Lookup Master Data (tampilkan nilai asli dalam pesan error)
            if (!$categoryId) $errors[] = "Kategori: '{$originalCategoryName}' tidak ditemukan di Master Data Kategori.";
            if (!$provinceId) $errors[] = "Provinsi: '{$originalProvinceName}' tidak ditemukan di Master Data Provinsi.";
            if (!$cityId) $errors[] = "Kota/Kabupaten: '{$originalCityName}' tidak ditemukan di Master Data Kabupaten.";

            if (!empty($errors)) {
                $this->failedRows[] = ['row' => $rowNumber, 'errors' => $errors, 'data' => $row->toArray()];
                continue; 
            }
            
            // --- 2. Pengecekan Duplikat Fuzzy (Levenshtein Distance) ---
            $nameToCheck = strtolower(trim($row['name']));
            $threshold = 3; // toleransi kemiripan (0 = identik, makin besar makin longgar)
            
            // Ambil semua nama StoreProspek aktif di kota yang sama
            $existingProspeks = StoreProspek::where('kota', $cityId)
                ->where('status', StoreProspek::STATUS["ACTIVE"])
                ->pluck('name');
            
            // Ambil semua nama Store aktif di kota yang sama
            $existingStores = Store::where('kota', $cityId)
                ->where('status', Store::STATUS["ACTIVE"])
                ->pluck('name');
            
            $isDuplicate = false;
            $duplicateSource = null;
            $duplicateName = null;
            
            // Cek terhadap StoreProspek
            foreach ($existingProspeks as $existingName) {
                $distance = levenshtein($nameToCheck, strtolower(trim($existingName)));
                if ($distance <= $threshold) {
                    $isDuplicate = true;
                    $duplicateSource = 'Prospek';
                    $duplicateName = $existingName;
                    break;
                }
            }
            
            // Jika belum duplikat, cek terhadap Store Existing
            if (!$isDuplicate) {
                foreach ($existingStores as $existingName) {
                    $distance = levenshtein($nameToCheck, strtolower(trim($existingName)));
                    if ($distance <= $threshold) {
                        $isDuplicate = true;
                        $duplicateSource = 'Existing';
                        $duplicateName = $existingName;
                        break;
                    }
                }
            }
            
            // Jika ditemukan kemiripan, catat error
            if ($isDuplicate) {
                $this->failedRows[] = [
                    'row' => $rowNumber,
                    'errors' => ["Nama hampir sama dengan {$duplicateSource} '{$duplicateName}' di Kota '{$originalCityName}'. Data '{$row['name']}' dianggap duplikat."],
                    'data' => $row->toArray()
                ];
                continue;
            }


            // --- 3. Proses Penyimpanan ---
            DB::beginTransaction();
            try {
                // Gabungkan telepon
                $phone = trim(implode(",", array_filter([$row['phone1'], $row['phone2']])), ',');

                // Simpan StoreProspek (Parent)
                $storeProspek = new StoreProspek();
                
                $formattedName = $this->formatCompanyName($row['name']);
                $storeProspek->name = $formattedName;
                $storeProspek->owner_name = $row['contact_person'] ?? null;
                $storeProspek->phone = $phone;
                $storeProspek->email = $row['email'] ?? null;
                $storeProspek->website = $row['website'] ?? null;
                $storeProspek->pic = $row['ao'] ?? null;
                $storeProspek->address = $row['address'] ?? null;
                
                // Set ID dari hasil Lookup
                $storeProspek->category_id = $categoryId;
                $storeProspek->provinsi = $provinceId;
                $storeProspek->kota = $cityId;
                
                // Menyimpan NAMA ASLI (Original Case) yang di-import
                $storeProspek->text_provinsi = $originalProvinceName; 
                $storeProspek->text_kota = $originalCityName;    
                
                // Field default/konstanta
                $storeProspek->count_member = 1;
                $storeProspek->status = StoreProspek::STATUS['ACTIVE'];
                $storeProspek->existence = 1;
                
                $storeProspek->save();
                
                // Simpan CustomerProspek (Member Default)
                $customerProspek = new CustomerProspek();
                
                $customerProspek->id = $storeProspek->id . '.' . $storeProspek->count_member;
                $customerProspek->customer_id = $storeProspek->id;
                $customerProspek->name = $formattedName;
                $customerProspek->contact_person = $storeProspek->owner_name ?? null;
                $customerProspek->phone = $storeProspek->phone;
                $customerProspek->address = $storeProspek->address;
                $customerProspek->provinsi = $storeProspek->provinsi;
                $customerProspek->kota = $storeProspek->kota;
                // Ambil nilai asli dari StoreProspek
                $customerProspek->text_provinsi = $storeProspek->text_provinsi;
                $customerProspek->text_kota = $storeProspek->text_kota;
                $customerProspek->officer = $row['officer'] ?? null;
                $customerProspek->zone = $row['zone'] ?? null; 
                $customerProspek->member_default = 1;
                $customerProspek->pengajuan = $row['pengajuan'] ?? $row['pic'];
                $customerProspek->additional_information =  $row['informasi_tambahan'] ?? null;
                $customerProspek->additional_notes = $row['catatan_tambahan'] ?? null;
                $customerProspek->status = CustomerProspek::STATUS['ACTIVE'];
                
                $customerProspek->save();

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Import Gagal di baris {$rowNumber}: " . $e->getMessage());
                $this->failedRows[] = ['row' => $rowNumber, 'errors' => ["Database Error: Gagal menyimpan data. ({$e->getMessage()})"], 'data' => $row->toArray()];
            }
        }
        
        // Tambahkan logging ringkasan untuk debugging
        $totalRows = $rows->count();
        $successCount = $totalRows - count($this->failedRows);
        Log::info("Import Summary: Total Rows={$totalRows}, Success={$successCount}, Failed=".count($this->failedRows));
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }

    public function getFailedRows(): array
    {
        return $this->failedRows;
    }
}
