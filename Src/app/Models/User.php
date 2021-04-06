<?php

namespace App\Models;

use App\Traits\ExtendModel;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, ExtendModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'super_admin_flg',
        'role',
        'department_id',
        'location',
        'approval',
        'memo',
        'leave_days',
        'leave_remaining_days',
        'leave_remaining_time',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected $appends = ['user_no'];

    // public function getUserNoAttribute()
    // {
    //     $user_no = str_pad($this->id, config('const.num_fillzero'), "0", STR_PAD_LEFT);
    //     return $user_no;
    // }

    public static function makeUserNoByAutoIncrementId(){
        return str_pad(static::getAutoIncrement(), config('const.num_fillzero'), "0", STR_PAD_LEFT);
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    /**
     * Prepare compact data for view
     * @param User|null User model
     * @return array
     */
    public static function getCompactData($user = null)
    {
        $compacts = [
            'locations' => config('const.location'),
            'roles' => config('const.role'),
            'approvals' => config('const.approval'),
            'departments' => Department::all(),
            'user' => $user
        ];

        return $compacts;
    }

    /**
     * Make validtor when register or edit user
     * @param array $inputs Data inputs
     * @param User|null $user User model
     * @param array|null $compactData Compact data for view
     * @param boolean|false $isIgnoreUniqueEmail Ignore user's ID when check unique email
     */
    public static function makeValidator($inputs, $user = null, $compactData = null, $isIgnoreUniqueEmail = false)
    {
        if ($compactData === null) {
            $compactData = static::getCompactData($user);
        }

        // get department IDs to check valid department inputed
        $departmentIds = Arr::pluck($compactData['departments']->toArray(), 'id');

        // rule mail
        $ruleMail = ['required', 'email'];
        if ($isIgnoreUniqueEmail) {
            $ruleMail[] = Rule::unique('users')->ignore($user->id, 'id');
            // $ruleMail[] = Rule::unique('users')->where(function($query) use($user){
            //     return $query->where('deleted_at', NULL)->where('id','<>', $user->id);
            // });
        } else {
            $ruleMail[] = 'unique:users';
        }

        $customAttributes = [
            'name' => __('validation.attributes.user.name')
        ];

        // make validator
        $validator = Validator::make($inputs, [
            'location' => ['required_select', Rule::in($compactData['locations'])],
            'department' => ['required_select', Rule::in($departmentIds)],
            'name' => 'required',
            'role' => ['required_select', Rule::in($compactData['roles'])],
            'email' => $ruleMail,
            'phone' => 'nullable|phone_number',
            'approval' => ['required_select', Rule::in($compactData['approvals'])],
            'leave_days' => 'required|numeric',
            'leave_remaining_days' => 'required|numeric',
            'leave_remaining_time' => 'required|numeric',
        ], [], $customAttributes);

        return $validator;
    }
}
