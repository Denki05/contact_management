<?php

namespace App\Imports;

use App\Master\Contact;
use App\Master\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ContactImport implements ToModel, WithHeadingRow
{
    private $errorMessages = []; // Array untuk menyimpan error

    public function model(array $row)
    {
        try {
            // Pastikan field 'store' tersedia
            if (!isset($row['store']) || empty(trim($row['store']))) {
                $this->errorMessages[] = "Kolom 'store' kosong atau tidak ditemukan.";
                return null;
            }

            // Pisahkan nama store dan kota
            $storeParts = explode(' ', trim($row['store']));
            $text_store = array_pop($storeParts); // Ambil bagian terakhir sebagai kota
            $name = implode(' ', $storeParts); // Gabungkan sisanya sebagai nama store

            // dd($text_store);

            // Cari customer berdasarkan nama dan text_store
            $customer = Customer::where('name', $name)
                                ->where('text_kota', $text_store)
                                ->first();

            if (!$customer) {
                $this->errorMessages[] = "Customer dengan nama '{$name}' dan text_store '{$text_store}' tidak ditemukan.";
                return null;
            }

            // Konversi tanggal lahir
            $dob = null;
            if (!empty($row['dob'])) {
                if (is_numeric($row['dob'])) {
                    // Konversi dari format Excel ke DateTime
                    $dob = Date::excelToDateTimeObject($row['dob'])->format('m-d'); // Ambil hanya bulan dan hari
                } else {
                    try {
                        $dob = Carbon::parse($row['dob'])->format('m-d'); // Ambil hanya bulan dan hari
                    } catch (\Exception $e) {
                        $this->errorMessages[] = "Format tanggal salah pada baris dengan nama '{$row['nama']}'.";
                        return null;
                    }
                }
                $dob = '1900-' . $dob; // Tambahkan tahun default (1900)
            }

            return new Contact([
                'name' => $row['nama'] ?? '',
                'position' => $row['posisi'] ?? '',
                'manage_id' => $customer->id, // Simpan ID Customer sebagai manage_id
                'phone' => $row['telepon'] ?? '',
                'dob' => $dob ?? null,
                'email' => $row['email'] ?? '',
                'ktp' => $row['ktp'] ?? '',
                'npwp' => $row['npwp'] ?? '',
                'is_for' => 0, // Contact biasa
                'status' => 1  // Status aktif
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal import baris: ' . json_encode($row) . ' dengan error: ' . $e->getMessage());
            $this->errorMessages[] = "Kesalahan sistem pada baris dengan nama '{$row['nama']}': " . $e->getMessage();
            return null;
        }
    }

    // Fungsi untuk mengambil error messages
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}