<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'photo_url',
        'bio',
        'is_verified',
        'is_active',
        'is_for_adults',
        'is_for_children',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'is_for_adults' => 'boolean',
        'is_for_children' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specializations()
    {
        return $this->hasMany(DoctorSpecialization::class);
    }

    public function helpTags()
    {
        return $this->belongsToMany(HelpTag::class, 'doctors_help_tags', 'doctor_id', 'tag_id');
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_doctor')
            ->withTimestamps();
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'doctors_images', 'doctor_id', 'image_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
