<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProspek extends Model
{
    use SoftDeletes;
    
    protected $appends = ['img_ktp', 'img_npwp'];
    protected $fillable = [
        'customer_id', 'member_default', 'pengajuan', 'officer', 'account_representative', 'account_representative_optional_1', 'account_representative_optional_2', 'name', 'contact_person', 'npwp', 'ktp', 'phone', 'address',
        'gps_latitude', 'gps_longitude',
        'provinsi', 'kota', 'kecamatan', 'kelurahan',
        'text_provinsi', 'text_kota', 'text_kecamatan', 'text_kelurahan',
        'zipcode', 'free_shipping', 'zone', 'setting_income_target', 'image_npwp', 'image_ktp', 'status', 'situation', 'status_key', 'rating', 'additional_information', 'additional_notes'
    ];
    protected $table = 'master_customer_other_addresses_prospek';
    public $incrementing = false;
    public static $directory_image = 'superuser_assets/media/master/member/';

    const STATUS = [
        'DELETED' => 0,
        'ACTIVE' => 1
    ];

    const SITUATION = [
        'INACTIVE' => 0,
        'ACTIVE' => 1
    ];

    const STATUS_KEY = [
        'DISABLED' => 0,
        'ENABLE' => 1
    ];

    const MEMBER_DEFAULT = [
        'NO' => 0,
        'YES' => 1
    ];

    const ZONING = [
        1 => 'JABODETABEK',
        2 => 'JABAR',
        3 => 'JATENG - JATIM',
        4 => 'SUMATERA',
        5 => 'BALI - KALIMANTAN - SULAWESI',
    ];

    const FREE_SHIPPING = [
        0 => 'NON FREE',
        1 => 'FREE',
    ];
    
    const PENGAJUAN = [
        1 => 'KANTOR',
        2 => 'ERICK',
        3 => 'LINDY',
        4 => 'KUMALA',
        5 => 'NIA',
    ];
    
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'manage_id');
    }

    public function store_prospek()
    {
        return $this->belongsTo('App\Master\StoreProspek', 'customer_id', 'id'); 
    }


    public function dokumen(){
        return $this->hasMany('App\Entities\Master\Dokumen','customer_other_address_id');
    }
    
    private function img_holder()
    {
        return asset('images/placeholder.png'); // Ganti dengan path URL gambar placeholder kamu yang sebenarnya
    }

    public function getImgKtpAttribute()
    {
        // Periksa apakah file gambar ada
        if ($this->image_ktp && File::exists(public_path(Self::$directory_image.$this->image_ktp))) {
            return asset(Self::$directory_image.$this->image_ktp);
        }
    
        // Jika file tidak ada, langsung kembalikan URL placeholder
        return asset('images/placeholder.png'); // Ganti dengan path URL placeholder kamu
    }

    public function getImgNpwpAttribute()
    {
        // Periksa apakah file gambar ada
        if ($this->image_npwp && File::exists(public_path(Self::$directory_image.$this->image_npwp))) {
            return asset(Self::$directory_image.$this->image_npwp);
        }
    
        // Jika file tidak ada, langsung kembalikan URL placeholder
        return asset('images/placeholder.png'); // Ganti dengan path URL placeholder kamu
    }

    public function routeNotificationForWhatsApp()
    {
        return $this->phone;
    }

    public function default()
    {
        return array_search($this->member_default, self::MEMBER_DEFAULT);
    }
    
    public function getPengajuanTextAttribute()
    {
        return self::PENGAJUAN[$this->pengajuan] ?? '-';
    }
}
