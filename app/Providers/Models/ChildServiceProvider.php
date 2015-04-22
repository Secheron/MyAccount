<?php namespace MyAccount\Providers\Models;

use MyAccount\Models\Child;
use Illuminate\Support\ServiceProvider;

class ChildServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('Child', function($app)
		{
		    return new Child($app);
		});
    }
}