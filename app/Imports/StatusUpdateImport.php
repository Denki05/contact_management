<?php

namespace App\Imports;

use App\Master\Store;
use App\Master\StoreProspek;
use App\Master\CustomerCategory;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class StatusUpdateImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (empty($row['id']) || empty($row['type'])) {
                continue;
            }

            $id = (int) $row['id'];
            $type = strtoupper(trim($row['type']));

            // Ambil model sesuai type
            $model = null;
            if ($type === 'EXISTING') {
                $model = Store::find($id);
            } elseif ($type === 'PROSPEK') {
                $model = StoreProspek::find($id);
            }

            if (!$model) continue;

            // ==============================
            // 1️⃣ Update STATUS jika ada
            // ==============================
            if (isset($row['status_baru']) && $row['status_baru'] !== '') {
                $statusValue = intval($row['status_baru']); // langsung integer 0/1/2
                $model->status = $statusValue;
            }

            // ==============================
            // 2️⃣ Update MAPPING/KATEGORI jika ada perubahan
            // ==============================
            if (!empty($row['mapping_kategori'])) {
                $newCategoryName = trim($row['mapping_kategori']);

                $category = CustomerCategory::where('name', $newCategoryName)
                    ->where('status', CustomerCategory::STATUS['ACTIVE'])
                    ->first();

                if ($category) {
                    $model->category_id = $category->id;
                }
            }

            // Simpan perubahan
            $model->save();
        }
    }
}