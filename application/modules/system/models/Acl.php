<?php

class System_Model_Acl extends Zend_Acl{
	private static $instance;
	protected $_auth;
	
    private function __construct() {
    	$this->_auth = Zend_Auth::getInstance();
    	
    	if(Zend_Auth::getInstance()->hasIdentity()){
	    	$identity = $this->_auth->getIdentity();
                 $identity->role_id = "001";
	  //  	var_dump($identity->role_id); exit;
	        //add roles
	        $addRole = $this->addRole(new Zend_Acl_Role($identity->role_id));
	        
	        $resources = new System_Model_DbTable_Resources();
	        $rowset = $resources->fetchAll();
	        
	        foreach ($rowset as $row) {
	        	$this->add(new Zend_Acl_Resource($row->id));
	        }
	        
	        $this->deny();
	        
	        $permissions = new System_Model_DbTable_Permissions();
               // var_dump($permissions);
             //   echo "it is ".$identity->role_id; die;
               
	        $select = $permissions->select()->where('role_id = ?', $identity->role_id);
	        $rowset = $permissions->fetchAll($select);
	        
	       	foreach ($rowset as $row) {
	       		if($this->has($row->resource_id)){
	            	$this->allow($identity->role_id, $row->resource_id);
	       		}
	        }
    	}
    }
    
    public static function getInstance(){
    	if(!self::$instance instanceOf System_Model_Acl){
    		self::$instance = new self();
    	}
    	
    	return self::$instance;
    }
    
    public function isAllowed($role = null, $resource = null, $privilege = null){
    	if($this->has($resource)){
    		return parent::isAllowed($role, $resource, $privilege);
    	}
    	
    	return true;
    }
}