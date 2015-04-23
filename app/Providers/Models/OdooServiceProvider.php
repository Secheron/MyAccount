<?php namespace MyAccount\Providers\Models;

use MyAccount\Models\Odoo;
use Illuminate\Support\ServiceProvider;

class OdooServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('Odoo', function()
		{
		    return new Odoo();
		});
    }
}