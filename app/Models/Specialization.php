<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = [
        'name',
    ];

    public function doctorSpecializations()
    {
        return $this->hasMany(DoctorSpecialization::class);
    }
}
