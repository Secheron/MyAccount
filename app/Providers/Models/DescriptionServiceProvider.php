<?php namespace MyAccount\Providers\Models;

use MyAccount\Models\Description;
use Illuminate\Support\ServiceProvider;

class DescriptionServiceProvider extends ServiceProvider {

    public function register()
    {
        $app = $this->app;

        $app['Description'] = function() {
            return new Description;
        };
    }
}