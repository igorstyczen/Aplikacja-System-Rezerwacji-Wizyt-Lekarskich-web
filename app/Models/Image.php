<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image',
        'description',
        'file_name',
        'original_file_name',
        'file_type',
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctors_images', 'image_id', 'doctor_id');
    }

    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'review_images', 'image_id', 'review_id');
    }
}
