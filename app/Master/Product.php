<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Alfa6661\AutoNumber\AutoNumberTrait;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
                        'id', 
                        'product_id', 
                        'warehouse_id', 
                        'packaging_id', 
                        'category_id', 
                        'type_id', 
                        'material_code', 
                        'material_name', 
                        'code', 
                        'name',
                        'price', 
                        'gender', 
                        'note', 
                        'product_finance_tax', 
                        'status',
                        'condition',
                        'updated_by',
                        'deleted_by',
                    ];

    protected $table = 'master_products_packaging';
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
        'ACTIVE' => 1
    ];

    const CONDITION = [
        'ENABLE' => 0, 
        'DISABLE' => 1, 
    ];

    public function product()
    {
        return $this->belongsTo('App\Entities\Master\Product', 'product_id', 'id');
    }

    public function type_product_pack()
    {
        return $this->belongsTo('App\Entities\Master\ProductType', 'type_id', 'id');
    }

    public function category_product_pack()
    {
        return $this->belongsTo('App\Entities\Master\ProductCategory', 'category_id', 'id');
    }

    public function cashback()
    {
        return $this->hasMany('App\Entities\Master\ProductCategoryType', 'product_packaging_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Entities\Master\Warehouse', 'warehouse_id');
    }

    public function packaging()
    {
        return $this->belongsTo('App\Master\Packaging', 'packaging_id');
    }

    public function setting_price_log()
    {
        return $this->hasMany('App\Entities\Penjualan\SettingPriceLog', 'product_packaging_id');
    }

    public function status()
    {
        return array_search($this->status, self::STATUS);
    }

    public function condition()
    {
        return array_search($this->condition, self::CONDITION);
    }

    public function kemasan()
    {
        $id_product = $this->id;
        
        $pecah = explode("-", $id_product);

        $packaging = Packaging::find($pecah[1]);

        return $packaging;
    }

    public function receiving_detail()
    {
        return $this->hasMany('App\Entities\Gudang\ReceivingDetail', 'product_packaging_id', 'id');
    }

    public function so_detail()
    {
        return $this->hasMany('App\Entities\Penjualan\SalesOrderItem', 'product_packaging_id');
    }

    public function stock_adjustments()
    {
        return $this->hasMany('App\Entities\Gudang\StockAdjustment', 'product_packaging_id');
    }
}
