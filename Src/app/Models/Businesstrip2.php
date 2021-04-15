<?php

namespace App\Models;

use App\Models\Transportation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Businesstrip2 extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = "businesstrip2s";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'destinations',
        'number_of_days',
        'total_daily_allowance',
        'total_daily_unit',
        'total_daily_rate',
        'daily_allowance',
        'daily_unit',
        'daily_rate',
        'other_fees',
        'other_fees_unit',
        'other_fees_rate',
        'other_fees_note',
        'charged_to',
        'under_instruction_date',
        'under_instruction_approval_no',
        'other_fees_note',
        'other_fees_note',
        'file_path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'tripfee_transportations',
        'tripfee_accomodations',
        'tripfee_communications',
    ];

    public function getTripFeeTransportationsAttribute()
    {
        $arr = [];
        foreach ($this->tripfees as $value) {
            if ($value->type_trip == config('const.trip_fee_type.transportation')){
                $arr[] = $value;
            }
        }
        return $arr;
    }

    public function getTripFeeAccomodationsAttribute()
    {
        $arr = [];
        foreach ($this->tripfees as $value) {
            if ($value->type_trip == config('const.trip_fee_type.accomodation')) {
                $arr[] = $value;
            }
        }
        return $arr;
    }

    public function getTripFeeCommunicationsAttribute()
    {
        $arr = [];
        foreach ($this->tripfees as $value) {
            if ($value->type_trip == config('const.trip_fee_type.communication')) {
                $arr[] = $value;
            }
        }
        return $arr;
    }

    public function transportations()
    {
        return $this->hasMany(Transportation::class);
    }

    public function tripfees()
    {
        return $this->hasMany(TripFee::class, 'businesstrip_id');
    }
}
