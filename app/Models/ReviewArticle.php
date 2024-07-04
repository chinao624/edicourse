<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'article_id',
        'status',
        'limit_time',
    ];

    protected $dates = [
        'limit_time',
    ];

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
