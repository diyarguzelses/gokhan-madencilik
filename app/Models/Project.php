<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'category_id', 'status','slug'];

    public function images()
    {
        return $this->hasMany(ProjectImage::class, 'project_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
