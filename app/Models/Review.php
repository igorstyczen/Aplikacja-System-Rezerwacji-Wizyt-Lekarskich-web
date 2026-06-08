<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'rating',
        'comment',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'review_images', 'review_id', 'image_id');
    }
}
