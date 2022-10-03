<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cred extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'key',
        'secure',
        'port'
    ];
}
