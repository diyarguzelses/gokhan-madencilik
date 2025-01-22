<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'url',
        'is_active',
        'order',
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }


    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
