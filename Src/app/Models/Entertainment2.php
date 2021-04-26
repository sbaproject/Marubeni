<?php

namespace App\Models;

use App\Models\Transportation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entertainment2 extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = "entertainment2s";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'entertainment_dt',
        'est_amount',
        'charged_to',
        'pay_info',
        'file_path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
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

    public function entertainmentinfos(){
        return $this->hasMany(EntertainmentInfos::class);
    }

    // Format data: [department_id_1]-[value1%],[department_id_2]-[value2%],...
    public static function explodeChargedBys($strVal)
    {
        if (empty($strVal)) {
            return [];
        }

        $arr = explode(',', $strVal);
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
    public static function implodeChargedBys($values)
    {
        $str = '';
        foreach ($values as $index => $item) {
            if ($index == 0) {
                $str .= $item['department'] . '-' . $item['percent'];
                continue;
            }
            $str .= ',' . $item['department'] . '-' . $item['percent'];
        }

        return $str;
    }
}
