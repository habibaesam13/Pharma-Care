<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favourite extends Model
{
    public $timestamps = true;

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected $table = 'favourites';

    protected function setKeysForSaveQuery($query)
    {
        return $query->where('user_id', $this->user_id)
                    ->where('product_id', $this->product_id);
    }

}
