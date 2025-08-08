<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestService extends Model
{
    protected $fillable = ['user_id', 'nurse_id', 'service_id', 'status', 'notes', 'address', 'requested_date', 'requested_time', 'nurse_type'];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function nurse()
{
    return $this->belongsTo(Nurse::class);
}

public function service()
{
    return $this->belongsTo(Service::class);
}

}
