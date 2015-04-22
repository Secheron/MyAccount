<?php namespace MyAccount\Models;

use Config;
use xml_rpc\xmlrpc;
use xml_rpc\xmlrpcs;
use ripcord\ripcord;

class OdooXmlRpc {

    public function __construct($user, $password) {
        //Get partner username and password (MD5) to make odoo requests
        $this->_user = $user;
        $this->_password = $password;

        //Get odoo.php Config
        $this->_host = Config::get('odoo.host');
        $this->_database = Config::get('odoo.database');
        $this->_rpcClientCommonText = Config::get('odoo.commonQuery');
        $this->_rpcClientObjectText = Config::get('odoo.objectQuery');

        //Get odoo server informations of connection
        $this->_rpcClientCommon = ripcord::client($this->_host.''.$this->_versionOdooRpcCommonText);
        $this->_uid = $this->_rpcClientCommon->authenticate($this->_database, $user, $password, array());
        $this->_rpcClientObject = ripcord::client($this->_host.''.$this->_versionOdooRpcObjectText);
    }

    /********************************************/
    /*  Properties                              */
    /*- - - - - - - - - - - - - - - - - - - - - */
    /*  $_host                                  */
    /********************************************/
    private $_user;
    private $_password;
    private $_host;
    private $_database;
    private $_uid;
    private $_rpcClientCommon;
    private $_rpcClientObject;
    private $_rpcClientCommonText;
    private $_rpcClientObjectText;
}