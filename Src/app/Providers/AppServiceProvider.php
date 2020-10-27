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
    }
}
