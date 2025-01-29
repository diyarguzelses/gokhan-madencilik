<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;



    // Veritabanı tablosunu belirleyin (varsayılan olarak 'settings' olacaktır, ancak farklı olabilir)
    protected $table = 'settings';

    // Veritabanındaki hangi sütunların alınacağına dair izinler (optional)
    protected $fillable = ['key', 'value'];

}


