<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='products';
    protected $fillable=[
        'name',
        'image',
        'category_id',
        'price',
        'brand',
        'discount_amount',
        'stock',
        'description',
    ];
protected $appends = ['price_after_discount'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function getPriceAfterDiscountAttribute()
{
    if ($this->discount_amount) {
        return round($this->price - ($this->price * $this->discount_amount / 100), 2);
    }

    return $this->price;
}

}
