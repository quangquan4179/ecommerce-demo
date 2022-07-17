<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_name',
        'brand',
        'price',
        'address',
        'image',
        'amount',
        'catalog_id',
    ];

    public function images(){
        return $this->hasMany(ListImagesProduct::class);
    }
    public function catalog(){
        return $this->belongsTo(Catalog::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
