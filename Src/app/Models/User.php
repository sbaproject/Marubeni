<?php

namespace App\Models;

use App\Http\Traits\MySoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_users';

    /**
     * Mass assignment
     */
    protected $fillable = ['id'];

    /**
     * Mutator
     */
    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }

    protected static function booted()
    {
    //     static::creating(function ($user) {
    //         $user->created_by = 1;
    //     });

    //     static::saving(function ($user) {
    //         dd($user);
    //         $user->updated_by = 2;
    //     });

    //     static::updating(function ($user) {
    //         dd($user);
    //         $user->updated_by = 2;
    //     });

        // static::deleting(function ($user) {
        //     $user->deleted_by = 2;
        // });

        // static::deleted(function ($user) {
        //     $user->deleted_by = 2;
        // });
    }

    // public function save(array $options = []){
    //     // new model
    //     if(!$this->exists){
    //         $this->created_by = 1;
    //     }
    //     // modified model
    //     else {
    //         $this->updated_by = 1;
    //     }
    //     parent::save()
    // }

    // public function update(array $attributes = [], array $options = []){
    //     dd($this->deleted_by);
    //     if(!empty($this->deleted_by)){
    //         $this->updated_by = 2;
    //     }
    //     parent::update();
    // }

    // public function delete(array $attributes = [], array $options = []){
    //     $this->deleted_by = 2;
    //     $this->update();
    //     parent::delete();
    // }
}
