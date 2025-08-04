<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    //
    protected $fillable = ['name', 'gender', 'phone', 'is_available'];

    public function services()
{
    return $this->belongsToMany(Service::class, 'nurse_services');
}

public function serviceRequests()
{
    return $this->hasMany(RequestService::class);
}


}
