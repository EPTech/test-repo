<?php
require_once 'Zend/Controller/Plugin/Abstract.php';

class My_Controller_Plugin_Auth2 extends Zend_Controller_Plugin_Abstract {
	/**
	 * Authentication instance
	 * @var Zend_Auth
	 */
	protected $_auth;
	
	
	/**
	 * User identity
	 * @var unknown
	 */
	protected $_identity;
	
	protected $_loginRequest;
	
	protected $_exceptions = array();
	
	private $_controller;
	
	private $_module;
	
	private $_action;
	
	private $_params;
	
	public function __construct($loginRequest, $exceptions = array()){
		$this->_loginRequest = $loginRequest;
		$storage = new Zend_Auth_Storage_Session('User_Session');
		$this->_auth = Zend_Auth::getInstance()->setStorage($storage);
		Zend_Registry::set('User_Session', $storage);
		$this->_identity = $this->_auth->getIdentity();
		
		if(!empty($exceptions)){
			$this->_exceptions = array_merge($this->_exceptions, $exceptions);
		}
	}
	
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
       	$this->_module = $request->getModuleName();
        $this->_controller = $request->getControllerName();
        $this->_action = $request->getActionName();
        $this->_params = $request->getParams();
	}
	
 	public function preDispatch(Zend_Controller_Request_Abstract $request) {    	
	    if (!$this->_auth->hasIdentity()){	        
	        if(!$this->_isLoginRequest()){
	        	$this->pass($request);
	        }
	    }else{
    		if($this->_isLoginRequest()){
	    		//trying to login when already logged in
	    		$this->_goHome();
	    	}
	    }
    } 
    
    private function _isLoginRequest(){
    	return $this->_controller == $this->_loginRequest['controller'] &&
    		$this->_module == $this->_loginRequest['module'] &&
    		$this->_action == $this->_loginRequest['action'];
    }
    
    /**
     * Determines if the access to the request should be authenticated
     * @param Zend_Controller_Request_Abstract $request
     */
    public function pass(Zend_Controller_Request_Abstract $request){
    	$pass = false;
    	
    	$module = $request->getModuleName();
    	$controller = $request->getControllerName();
    	$action = $request->getActionName();
    	
    	foreach ($this->_exceptions as $exception){
        	$eModule = isset($exception['module']) ? $exception['module'] : $module;
        	$eController = isset($exception['controller']) ? $exception['controller'] : $controller;
        	$eAction = isset($exception['action']) ? $exception['action'] : $action;
        	        		
        	if((($eModule 		== $module) &&
        		($eController 	== $controller) &&
        		($eAction 		== $action))){
        		
        		$pass = true;
        		break;
        	}
        }
        	
       	if(!$pass){
		    $this->requestLogin();
        }
    }
    
    private function _goHome(){
    	header('Location: /');
		$this->_request->setDispatched();
    }
    
    public function requestLogin(){
    	$this->getRequest()
    		->setActionName($this->_loginRequest['action'])
    		->setControllerName($this->_loginRequest['controller'])
    		->setModuleName($this->_loginRequest['module'])
    	->setDispatched(false);
    } 
    
}