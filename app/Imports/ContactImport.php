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
            // Pastikan semua field tersedia
            if (!isset($row['store']) || empty(trim($row['store']))) {
                $this->errorMessages[] = "Kolom 'store' kosong atau tidak ditemukan.";
                return null;
            }

            // Cari store berdasarkan nama customer
            $customer = Customer::where('name', trim($row['store']))->first();
            if (!$customer) {
                $this->errorMessages[] = "Customer dengan nama '{$row['store']}' tidak ditemukan.";
                return null;
            }

            // Konversi tanggal lahir
            $dob = null;
            if (!empty($row['dob'])) {
                if (is_numeric($row['dob'])) {
                    $dob = Date::excelToDateTimeObject($row['dob'])->format('Y-m-d');
                } else {
                    try {
                        $dob = Carbon::parse($row['dob'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $this->errorMessages[] = "Format tanggal salah pada baris dengan nama '{$row['nama']}'.";
                        return null;
                    }
                }
            }

            return new Contact([
                'name' => $row['nama'] ?? '',
                'position' => $row['posisi'] ?? '',
                'manage_id' => $customer->id, // Simpan ID Customer sebagai manage_id
                'phone' => $row['telepon'] ?? '',
                'dob' => $dob,
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