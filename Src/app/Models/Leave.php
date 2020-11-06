<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'code_leave',
        'paid_type',
        'reason_leave',
        'date_from',
        'date_to',
        'time_day',
        'time_from',
        'time_to',
        'maternity_from',
        'maternity_to',
        'file_path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
