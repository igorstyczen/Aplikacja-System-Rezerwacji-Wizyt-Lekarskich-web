<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpTag extends Model
{
    protected $fillable = [
        'tag_name',
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctors_help_tags', 'tag_id', 'doctor_id');
    }
}
