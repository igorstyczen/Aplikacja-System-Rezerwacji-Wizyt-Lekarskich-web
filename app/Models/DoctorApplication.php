<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorApplication extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'bio',
        'specialization_ids',
        'help_tag_ids',
        'clinic_name',
        'clinic_city',
        'clinic_address',
        'clinic_details',
        'is_for_adults',
        'is_for_children',
        'status',
        'admin_note',
        'reviewed_at',
    ];

    protected $casts = [
        'specialization_ids' => 'array',
        'help_tag_ids' => 'array',
        'is_for_adults' => 'boolean',
        'is_for_children' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
