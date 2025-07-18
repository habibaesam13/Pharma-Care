<?php

namespace App\Models;

use App\Models\User;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table='carts';
    protected $fillable=[
        'user_id',
        'subtotal',
    ];
    protected $hidden = ['subtotal'];
    public function user()
{
    return $this->belongsTo(User::class);
}
public function items()
{
    return $this->hasMany(CartItem::class);
}

public function subtotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}
