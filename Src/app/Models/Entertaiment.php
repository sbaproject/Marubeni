<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entertaiment extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    //  protected $table = 'entertaiments';

    protected $appends = ['entertainment_infos'];

    public function getEntertainmentInfosAttribute()
    {
        $infos = EntertainmentInfos::where('entertaiment_id', $this->id)->get();
        return $infos->toArray();
    }
}
