<?php namespace MyAccount\Models;

use Illuminate\Database\Eloquent\Model;
use MyAccount\Config\odoo;
use MyAccount\ressource\lib\OdooXmlRpc;

class Partner extends Model  {

	protected $table = 'partners';

		/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
	



	public function __construct($app)
	{

		$this->getRightUser();
	}

	//For test, the username and password will be come from login view

	private function getRightUser () {

		$this->hello = "hello";
		var_dump($this->hello);

	}

}