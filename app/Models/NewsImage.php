<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news_images';
    protected $fillable = ['news_id', 'image'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
