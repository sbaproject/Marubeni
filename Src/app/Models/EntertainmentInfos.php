<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainmentInfos extends Model
{
    use HasFactory;

    protected $table = 'entertaiment_infos';

    protected $fillable = [
        'cp_name',
        'cp_country',
        'cp_address',
        'cp_department',
        'title',
        'name_attendants',
        'details_dutles',
    ];
}
