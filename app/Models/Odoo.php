<?php namespace MyAccount\Models;

use Request as Req;
use Redis;

use Illuminate\Http\Request;
use Config;
use ripcord;


class Odoo  {

    public function __construct() {
       
    }

    private $user;
    private $password;
    private $host;
    private $database;
    private $uid;
    private $rpcClientCommon;
    private $rpcClientObject;
    private $rpcClientCommonText;
    private $rpcClientObjectText;

    public function SaveInstanceToCache () {
        /*
        $redis = Redis::connection();
        $redis->set('name', $username);
        $name = $redis->get('name');
        */
    }

    public function GetInstanceFromCache () {
    }


    public function LoginOdoo($credentials) {
         //Get partner username and password (MD5) to make odoo requests

        $this->user = $credentials['username'];
        $this->password = $credentials['password'];

        //Get odoo.php Config
        $this->host = Config::get('odooServer.host');
        $this->database = Config::get('odooServer.database');
        $this->rpcClientCommonText = Config::get('odooServer.commonQuery');
        $this->rpcClientObjectText = Config::get('odooServer.objectQuery');


        //Get odoo server informations of connection
        $this->rpcClientCommon = ripcord::client($this->host.''.$this->rpcClientCommonText);
        $this->uid = $this->rpcClientCommon->authenticate($this->database, $this->user, $this->password, array());
        $this->rpcClientObject = ripcord::client($this->host.''.$this->rpcClientObjectText);


        if(!$this->uid) {
            //Make exception
        } else {
            $this->SaveInstanceToCache();
        }
    }


    public function ExecuteAndGetOdooMethod($odooModel, $odooMethod, $arrayParameters) {
        
        try {
            $result = $this->rpcClientObject->execute_kw(
                $this->database,
                $this->uid,
                $this->password,
                $odooModel,
                $odooMethod,
                $arrayParameters
            );
        } catch (Exception $e) {
            //Make exception
        }

        return $result;
    }



}