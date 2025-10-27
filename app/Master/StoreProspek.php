<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProspek extends Model
{
    use SoftDeletes;
    
    protected static $pic;
    protected $fillable = [
        'category_id', 'count_member', 'code', 'name',
        'email', 'phone', 'npwp', 'ktp', 'has_ppn', 'has_tempo', 'tempo_limit', 'address',
        'owner_name', 'plafon_piutang', 'saldo', 'gps_latitude', 'gps_longitude', 'zone',
        'provinsi', 'kota', 'kecamatan', 'kelurahan',
        'text_provinsi', 'text_kota', 'text_kecamatan', 'text_kelurahan',
        'zipcode', 'image_npwp', 'image_ktp', 'image_store', 'notification_email', 'status', 'existence', 'pic'
    ];
    protected $table = 'master_customers_prospek';
    public static $directory_image = 'superuser_assets/media/master/customer/';

    const STATUS = [
        'INACTIVE' => 0,
        'ACTIVE' => 1,
        'DELETED' => 2,
    ];

    const EXISTENCE = [
        'DISABLED' => 0,
        'ENABLE' => 1,
    ];

    const HAS_TEMPO = [
        'NO' => 0,
        'YES' => 1
    ];

    public function category()
    {
        return $this->belongsTo('App\Master\CustomerCategory', 'category_id');
    }


    public function member_prospek()
    {
        return $this->hasMany('App\Master\CustomerProspek', 'customer_id', 'id');
    }

    public function has_tempo()
    {
        return array_search($this->has_tempo, self::HAS_TEMPO);
    }

    public function status()
    {
        return array_search($this->status, self::STATUS);
    }

    public function existence()
    {
        return array_search($this->existence, self::EXISTENCE);
    }

    public function member_count()
    {
        $data = [];

        // dd($this->id);
        $get_member = CustomerProspek::where('customer_id', $this->id)->get();

        foreach ($get_member as $item) {
            // dd($item->name);
            $data[] = [
                'member_id' => $item->id,
                'member_name' => $item->name,
                'member_city' => $item->text_kota,
                'member_default' => $item->default(),
                'member_latitude' => $item->gps_latitude,
                'member_longtitude' => $item->gps_longitude,
                'member_condition' => $item->situation,
                'member_status_key' => $item->status_key,
            ];
        }

        return $data;
    }
}
