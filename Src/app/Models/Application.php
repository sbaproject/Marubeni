<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'form_id',
        'group_id',
        'current_step',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function Form(){
        return $this->belongsTo('App\Models\Form');
    }

    // public function Leave(){
    //     return $this->hasMany('App\Models\Leave','application_id','id');
    // }
}
