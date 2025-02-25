<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'packaging_id', 'category_id', 'brand_reference_id', 'sub_brand_reference_id', 'brand_name', 'count_pack',
        'code', 'name', 'material_code', 'material_name', 'material_code_optional', 'material_name_optional', 'alias', 'description', 
        'default_quantity', 'default_unit_id', 'ratio', 'default_warehouse_id', 
        'vendor_id', 'vendor_optional_id', 'buying_price', 'selling_price', 'image', 'image_hd', 'videos_product_1', 'videos_product_2',
        'status', 'product_finance_tax', 'gender'
    ];
    protected $table = 'master_products';
    public $incrementing = false;

    const NOTE = [
        'BEST SELLER',
        'NEW',
        'RECOMMENDATION',
        'REGULER',
        'SAMPLE',
    ];

    const GENDER = [
        'MALE',
        'FEMALE',
        'UNISEX',
    ];

    const STATUS = [
        'DELETED' => 0,
        'ACTIVE' => 1,
        'INACTIVE' => 2,
        'ENABLE' => 3,
        'DISABLE' => 4
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? url('file/product/' . basename($this->image)) : asset('default-image.png');
    }
    
    public function getImageHdUrlAttribute()
    {
        return $this->image_hd ? url('file/product/' . basename($this->image_hd)) : asset('default-image.png');
    }
    
    public function getVideoProductUrlAttribute()
    {
        return $this->videos_product_1 ? url('file/product/' . basename($this->videos_product_1)) : null;
    }
    
    public function getVideoSosmedUrlAttribute()
    {
        return $this->videos_product_2 ? url('file/product/' . basename($this->videos_product_2)) : null;
    }
}