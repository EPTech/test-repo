<?php

abstract class My_Controller_SecureAction extends Zend_Controller_Action{
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
	
	protected $_passCallback;
	
	private $_controller;
	
	private $_module;
	
	private $_action;
	
	private $_params;
	
	public function __construct($passCallback=null){
		$this->_loginRequest = $loginRequest;
		$storage = new Zend_Auth_Storage_Session('Payporte_User_Session');
		$this->_auth = Zend_Auth::getInstance()->setStorage($storage);
		Zend_Registry::set('Payporte_User_Session', $storage);
		$this->_identity = $this->_auth->getIdentity();
		$this->_passCallback = $passCallback;
	}
	
	public function dispatchLoopStartup($request){
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
	
	public function pass($request){
		$pass = false;
		 
		if(is_callable($this->_passCallback)){
			$pass = call_user_func($this->_passCallback, $request);
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
		->setModuleName($this->_loginRequest['module']);
	}
}