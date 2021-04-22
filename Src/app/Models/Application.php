<?php

namespace App\Models;

use App\Models\User;
use App\Traits\ExtendModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory, Notifiable, SoftDeletes, ExtendModel;

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
        'comment',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['last_approval_step_1'];

    public function getLastApprovalStep1Attribute()
    {
        $conditions = [
            'application_id' => $this->id,
            'status' => config('const.application.status.completed'),
            'step' => config('const.application.step_type.application'),
        ];

        return HistoryApproval::getHistory($conditions)->first();
    }

    public static function makeApplicationNoByAutoIncrementId($formId)
    {
        $autoIncrementId = static::getAutoIncrement();
        return config('const.form_prefix')[$formId] . '-' . str_pad($autoIncrementId, config('const.num_fillzero'), "0", STR_PAD_LEFT);
    }

    public function Form()
    {
        return $this->belongsTo('App\Models\Form');
    }

    public function leave()
    {
        return $this->hasOne(Leave::class);
    }

    public function business()
    {
        return $this->hasOne(Businesstrip::class);
    }

    public function business2()
    {
        return $this->hasOne(BusinessTrip2::class);
    }

    public function entertainment()
    {
        return $this->hasOne(Entertaiment::class);
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function Leave(){
    //     return $this->hasMany('App\Models\Leave','application_id','id');
    // }
}
