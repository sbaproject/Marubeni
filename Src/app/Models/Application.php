<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $appends = ['application_no'];

    public function getApplicationNoAttribute()
    {
        $prefix = config('const.form_prefix.'.$this->form_id);
        $application_no = $prefix.'-'.str_pad($this->id, config('const.num_fillzero'), "0", STR_PAD_LEFT);
        return $application_no;
    }

    public function Form(){
        return $this->belongsTo('App\Models\Form');
    }

    // public function Leave(){
    //     return $this->hasMany('App\Models\Leave','application_id','id');
    // }
}
