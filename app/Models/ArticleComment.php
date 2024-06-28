<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    use HasFactory;

    protected $fillable = ['professor_id', 'article_id', 'comment'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
}
