<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;

class CustomerCategory extends Model
{
    protected $fillable = ['code', 'name', 'score', 'description', 'status'];
    protected $table = 'master_customer_categories';

    const STATUS = [
        'DELETED' => 0,
        'ACTIVE' => 1
    ];

    public function types()
    {
        return $this->belongsToMany('App\Entities\Master\CustomerType', 'master_customer_category_types', 'category_id', 'type_id')->withPivot('id');
    }

    public function store_prospek()
    {
        return $this->hasMany('App\Master\StoreProspek', 'category_id')->orderBy('name');
    }
}
