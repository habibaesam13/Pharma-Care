<?php

namespace App\Models;

use App\Models\User;
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
    public function favouritedByUsers()
{
    return $this->belongsToMany(User::class, 'favourites')->withTimestamps();
}

    public function getPriceAfterDiscountAttribute()
{
    if ($this->discount_amount) {
        return round($this->price - ($this->price * $this->discount_amount / 100), 2);
    }

    return $this->price;
}
public function cartItems()
{
    return $this->hasMany(CartItem::class);
}

}
