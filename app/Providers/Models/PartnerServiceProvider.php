<?php namespace MyAccount\Providers\Models;

use MyAccount\Models\Partner;
use Illuminate\Support\ServiceProvider;

class PartnerServiceProvider extends ServiceProvider {

    public function register()
    {

        $this->app->bind('Partner', function($app)
		{
		    return new Partner($app);
		});
    }
}