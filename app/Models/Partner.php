<?php namespace MyAccount\Models;

use Request as Req;
use Redis;
use Illuminate\Http\Request;

class Partner {

	public function __construct()
	{

	}

	private $id;
    private $code;
    private $isCompany;
    private $email;
    private $phone;
    private $city;
    private $zip;
    private $street1;
    private $street2;
    private $street3;
    private $firstname;
    private $lastname;
    private $name;
    private $language;
    private $gender;
    private $_listChilds = array();

	public function SaveInstanceToCache () {
        $redis = Redis::connection();

        $instance = [
        		'id' => $this->id,
        		'code' => $this->code,
        		'isCompany' => $this->isCompany,
        		'email' => $this->email,
    			'phone' => $this->phone,
   				'city' => $this->city,
				'zip' => $this->zip,
				'street1' => $this->street1,
				'street2' => $this->street2,
				'street3' => $this->street3,
				'firstname' => $this->firstname,
				'lastname' => $this->lastname,
				'name' => $this->name,
				'language' => $this->language,
				'gender' => $this->gender
        		];

        $redis->HMSET($this->id, $instance);
    }

    public function SaveInstanceFromCache ($partnerId) {
		$redis = Redis::connection();
    	
    	$instance = $redis->HGETALL($partnerId);

    	$this->id = $instance['id'];
		$this->code = $instance['code'];
		$this->isCompany = $instance['isCompany'];
		$this->email = $instance['email'];
		$this->phone = $instance['phone'];
		$this->city = $instance['city'];
		$this->zip = $instance['zip'];
		$this->street1 = $instance['street1'];
		$this->street2 = $instance['street2'];
		$this->street3 = $instance['street3'];
		$this->firstname = $instance['firstname'];
		$this->lastname = $instance['lastname'];
		$this->name = $instance['name'];
		$this->language = $instance['language'];
		$this->gender = $instance['gender'];
    }

	public function SetPartnerInformations ($credentials, $type) {
		//Need to be implemented with sessions
		if($type === "odoo") {

			$app = app();
			$odoo = $app->make('Odoo');

			//Need to be store in session
			$odoo->LoginOdoo($credentials);

			$instance = $odoo->ExecuteAndGetOdooMethod(
				'res.partner', 
				'get_partner_from_user',
				//Need to implement default value in ExecuteAndGetOdooMethod
				array(1)
				);

			//Need to implement this instance informations in properties of 
			//this partner, and aften then store the partner in cach with call SaveInstanceToCache
			var_dump($instance);
		}
	}

    public function AddChild(&$value) {
        $this->_listChilds[] = $value;
    }

    public function GetChild($child) {
        $childToReturn = null;
        foreach ($this->_listChilds as $childToTest) 
        {
            if ($child->GetId() === $childToTest->GetId())
            {
                $childToReturn = $childToTest;
            }
        }        
        return $childToReturn;
    }

    public function GetChilds() {
        $listChilds = $this->_listChilds;
        return $listChilds;
    }

    public function RemoveChild($childToRemove) {
        $i = 0;
        $exist = false;
        
        //Unset the index of array where the description is found
        //If the description isn't in the list, throw a exception
        foreach ($this->_listChilds as $child) 
        {
            if ($child->GetId() === $childToRemove->GetId())
            {
                unset($this->_listChilds[$i]);
                $i++;
                $exist = true;
            }
        }
        
        if(!$exist) {
            //Need to be implemented: throw new Exception('Partner::RemoveChild: Child can\'t be removed');
        }
    }


}