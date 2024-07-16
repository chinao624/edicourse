<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewArticle extends Model
{
    protected $table = 'review_articles';
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_WITHDRAWN = 'withdrawn';
    const STATUS_THANKED = 'thanked'; 

    protected $fillable = [
        'reviewer_id',
        'article_id',
        'status',
        'limit_time',
        'withdrawn_at',
        'feedback',
        
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
