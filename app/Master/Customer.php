<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;
use App\Master\Contact;

class Customer extends Model
{
    protected $fillable = [
        'customer_id', 'member_default', 'officer', 'account_representative', 'account_representative_optional_1', 'account_representative_optional_2', 'name', 'contact_person', 'npwp', 'ktp', 'phone', 'address',
        'gps_latitude', 'gps_longitude',
        'provinsi', 'kota', 'kecamatan', 'kelurahan',
        'text_provinsi', 'text_kota', 'text_kecamatan', 'text_kelurahan',
        'zipcode', 'free_shipping', 'zone', 'setting_income_target', 'image_npwp', 'image_ktp', 'status', 'situation', 'status_key'
    ];
    protected $table = 'master_customer_other_addresses';
    public $incrementing = false;

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
    
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'manage_id');
    }

    public function store_existing()
    {
        return $this->belongsTo('App\Master\Store', 'customer_id'); 
    }

    public function dokumen(){
        return $this->hasMany('App\Entities\Master\Dokumen','customer_other_address_id');
    }

    

    

    public function default()
    {
        return array_search($this->member_default, self::MEMBER_DEFAULT);
    }

    
}
