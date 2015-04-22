<?php namespace MyAccount\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model {

	public function __construct($app)
	{
	}

	//
	public function hello () {
		return "hello";
	}
}