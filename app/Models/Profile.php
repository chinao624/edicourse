<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_name',
        'birth_year',
        'birth_month',
        'birth_day',
        'introduction',
        'icon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

