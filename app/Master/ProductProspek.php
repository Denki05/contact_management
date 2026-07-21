<<<<<<< HEAD
<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;

class ProductProspek extends Model
{
    // Nama tabel (opsional jika nama tabel = product_prospek)
    protected $table = 'master_products_prospek';

    // Kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'kode',
        'nama',
        'searah',
        'harga',
        'brand'
    ];

    // Jika ingin cast tipe data tertentu
    protected $casts = [
        'harga' => 'decimal:2',
    ];
    
    const KATEGORI = [
        1 => 'NEW',
        2 => 'SAMPLE',
    ];
}
=======
<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;

class ProductProspek extends Model
{
    // Nama tabel (opsional jika nama tabel = product_prospek)
    protected $table = 'master_products_prospek';

    // Kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'kode',
        'nama',
        'searah',
        'harga',
        'brand'
    ];

    // Jika ingin cast tipe data tertentu
    protected $casts = [
        'harga' => 'decimal:2',
    ];
    
    const KATEGORI = [
        1 => 'NEW',
        2 => 'SAMPLE',
    ];
}
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
