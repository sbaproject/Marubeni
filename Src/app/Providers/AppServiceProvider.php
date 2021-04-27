<?php

namespace App\Providers;

use Illuminate\Validation\Rule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // regist custom validation rules
        $this->registCustomValidations();

        // regist boostrap for paginator
        Paginator::useBootstrap();
    }

    /**----------------------------/
     * Cusmtom validations
     *----------------------------*/
    public function registCustomValidations(){
        // phone number rule
        Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
            $pattern = '/^(0|\+84)(\s|\.)?((3[2-9])|(5[689])|(7[06-9])|(8[1-689])|(9[0-46-9]))(\d)(\s|\.)?(\d{3})(\s|\.)?(\d{3})$/';
            return preg_match($pattern, $value);
        });

        // selection required
        Validator::extend('required_select', function ($attribute, $value, $parameters, $validator) {
            if($value === null || trim($value) === '' || $value === 'empty'){
                return false;
            }
            return true;
        });

        // equal value
        Validator::extend('equal', function ($attribute, $value, $parameters, $validator) {
            // dd($parameters);
            if ($value != $parameters[0]) {
                return false;
            }
            return true;
        });
        Validator::replacer('equal', function ($message, $attr, $rule, $parameters) {
            $message = str_replace(':value', $parameters[0], $message);
            return str_replace(':suffix', $parameters[1], $message);
        });
    }
}
