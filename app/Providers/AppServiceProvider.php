<?php

namespace App\Providers;

use App\Channels\SmsChannel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Config;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Config::set('adminlte.logo', '<img src="'.url('/images/logo.png').'" />');

        Arr::macro('unDot', function ($dotNotationArray) {
            $array = [];
            foreach ($dotNotationArray as $key => $value) {
                Arr::set($array, $key, $value);
            }

            return $array;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        $this->app->bind(SmsChannel::class, function () {
            $smsDriverClass = config('constants.sms.driver_class');
            $smsDriverClass = 'App\Channels\\' . $smsDriverClass . '';

            return $this->app->make($smsDriverClass);
        });

        // If you are running lower version of MySQL then you may get following error : 1071 Specified key was too long; max key length is 767 bytes
        // To fix this, I'm setting max string length of all db fields by default to 191
        Schema::defaultStringLength(191); //Solved by decreasing StringLength to 191 instead of by default 255
        //Add this custom validation rule.
        Validator::extend('alpha_spaces', function ($attribute, $value) {

            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);

        });

        /**
         * Somehow PHP is not able to write in default /tmp directory and SwiftMailer was failing.
         * To overcome this situation, we set the TMPDIR environment variable to a new value.
         */
       /* if (class_exists('Swift_Preferences')) {
            \Swift_Preferences::getInstance()->setTempDir(storage_path() . '/tmp');
        } else {
            \Log::warning('Class Swift_Preferences does not exists');
        }*/
    }
}
