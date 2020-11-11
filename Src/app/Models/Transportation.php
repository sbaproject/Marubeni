<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure',
        'arrive',
        'method',
        'created_at',
        'updated_at',
    ];
}
