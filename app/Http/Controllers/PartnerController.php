<?php namespace MyAccount\Http\Controllers;

use MyAccount\Models\PartnerServiceProvider;
use MyAccount\Models\Partner;
use Config;

class PartnerController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/
	public function index()
	{
		$this->CreatePartner();
	}

	public function CreatePartner()
	{
		$app = app();

		$binding = $app->make('Partner');
		print_r($binding);

		

		return view('debugView');
	}
}