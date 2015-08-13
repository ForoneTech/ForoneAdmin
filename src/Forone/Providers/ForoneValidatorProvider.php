<?php
/**
 * User: Mani Wang
 * Date: 8/13/15
 * Time: 9:16 PM
 * Email: mani@forone.co
 */

namespace Forone\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class ForoneValidatorProvider extends ServiceProvider {

    public function boot()
    {
        Validator::extend('mobile', function ($attribute, $value, $parameters) {
            return preg_match("/^1[0-9]{2}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$value);
        });

        Validator::extend('code', function ($attribute, $value, $parameters) {
            return strlen($value) != 6 || !preg_match("/[0-9]{6}/", $value);
        });
    }

}