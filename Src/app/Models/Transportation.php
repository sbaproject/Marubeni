<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transportation extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'businesstrip_id',
        'businesstrip2_id',
        'trans_date',
        'departure',
        'arrive',
        'method',
        'created_at',
        'updated_at',
    ];
}
