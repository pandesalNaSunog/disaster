<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        'image',
        'response',
        'barangay_id',
        'disaster_category_id',
        'read',
        'lat',
        'long'
    ];
}
