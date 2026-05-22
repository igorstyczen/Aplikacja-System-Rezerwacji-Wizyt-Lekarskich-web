<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSpecialization extends Model
{
    protected $fillable = [
        'doctor_id',
        'specialization_id',
        'specialization_name',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
