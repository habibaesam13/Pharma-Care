<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $fillable = ['name'];
    public function nurses()
{
    return $this->belongsToMany(Nurse::class, 'nurse_services');
}

public function serviceRequests()
{
    return $this->hasMany(RequestService::class);
}

}
