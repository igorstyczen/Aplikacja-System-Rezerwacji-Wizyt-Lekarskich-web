<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $fillable = [
        'doctor_id',
        'name',
        'address',
        'city',
        'details',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function availabilitySlots()
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
