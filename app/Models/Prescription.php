<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $table = 'prescriptions';
    protected $fillable = [
        'patient_id',
        'prescription_image',
        'notes',
        'prescription_date',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

}
