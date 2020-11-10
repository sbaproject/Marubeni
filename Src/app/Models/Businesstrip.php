<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Businesstrip extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'destinations',
        'trip_dt_from',
        'trip_dt_to',
        'accommodation',
        'accompany',
        'borne_by',
        'comment',
        'file_path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

}
