<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripFee extends Model
{
    use HasFactory, Notifiable;

    protected $table = "tripfees";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'businesstrip_id',
        'type_trip',
        'trip_no',
        'method',
        'unit',
        'exchange_rate',
        'amount',
        'note',
        'created_by',
        'updated_by',
    ];
}
