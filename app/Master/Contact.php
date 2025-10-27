<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;
use App\Master\Customer;
use App\Master\CustomerProspek;

class Contact extends Model
{
    protected $table = 'master_contacts';
    public $incrementing = false;

    protected $fillable = [
        'manage_id',
        'name',
        'phone',
        'email',
        'position',
        'dob',
        'npwp',
        'ktp',
        'image_ktp',
        'image_npwp',
        'address',
        'status',
    ];

    protected $appends = [
        'img_ktp',
        'img_npwp',
        'customer_name',
        'customer_city',
        'source_label',
    ];

    const STATUS = [
        'DELETED' => 0,
        'ACTIVE'  => 1,
    ];

    public static $directory_image = 'superuser_assets/media/master/contact/';

    /**
     * Ambil data customer (Existing/Prospek) tanpa relasi formal.
     */
    public function getRelatedCustomer()
    {
        if (!$this->manage_id) {
            return null;
        }

        $customer = Customer::find($this->manage_id);
        if (!$customer) {
            $customer = CustomerProspek::find($this->manage_id);
        }

        return $customer;
    }

    // --- Accessor Gambar ---
    public function getImgKtpAttribute()
    {
        $path = self::$directory_image . $this->image_ktp;
        return $this->image_ktp && file_exists(public_path($path))
            ? asset($path)
            : img_holder();
    }

    public function getImgNpwpAttribute()
    {
        $path = self::$directory_image . $this->image_npwp;
        return $this->image_npwp && file_exists(public_path($path))
            ? asset($path)
            : img_holder();
    }

    // --- Accessor Nama & Kota Customer ---
    public function getCustomerNameAttribute()
    {
        $related = $this->getRelatedCustomer();
        return $related ? $related->name : '-';
    }

    public function getCustomerCityAttribute()
    {
        $related = $this->getRelatedCustomer();
        return $related ? $related->text_kota : '-';
    }

    // --- Label Sumber ---
    public function getSourceLabelAttribute()
    {
        if (!$this->manage_id) {
            return '-';
        }

        return Customer::find($this->manage_id) ? 'Existing' : 'Prospek';
    }
}