<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'mainimg', 'title', 'lead', 'img1', 'cap1', 'img2', 'cap2', 'closing', 'genre'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
{
    return $this->hasMany(ArticleComment::class);
}

   }
