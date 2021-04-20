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
        'charged_to',

        'daily1_amount',
        'daily1_days',
        'daily2_amount',
        'daily2_rate',
        'daily2_days',

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
        'tripfee_otherfees',
        'chargedbys',
    ];

    public function getChargedBysAttribute()
    {
        return static::explodeChargedBys($this->charged_to);
    }

    public function setChargedBysAttribute($values)
    {
       $this->charged_to = static::implodeChargedBys($values);
    }

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

    public function getTripFeeOtherFeesAttribute()
    {
        $arr = [];
        foreach ($this->tripfees as $value) {
            if ($value->type_trip == config('const.trip_fee_type.otherfees')) {
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

    // Format data: [department_id_1]-[value1%],[department_id_2]-[value2%],...
    public static function explodeChargedBys($strVal)
    {
        if(empty($strVal)){
            return [];
        }

        $arr = explode(',',$strVal);
        $rs = [];
        foreach ($arr as $value) {
            $items = explode('-', $value);
            $rs[] = [
                'department' => $items[0],
                'percent' => $items[1],
            ];
        }

        return $rs;
    }

    // Format data: [department_id_1]-[value1%],[department_id_2]-[value2%],...
    public static function implodeChargedBys($values){
        $str = '';
        foreach ($values as $index => $item) {
            if ($index == 0){
                $str .= $item['department'] . '-' . $item['percent'];
                continue;
            }
            $str .= ','.$item['department'] . '-' . $item['percent'];
        }

        return $str;
    }
}
