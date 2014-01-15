<?php
require_once 'Zend/Controller/Plugin/Abstract.php';

class My_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract {
	/**
	 * Authentication instance
	 * @var Zend_Auth
	 */
	protected $_auth;
	
	/**
	 * The adapter used for authenticating
	 * @var Zend_Auth
	 */
	protected $_adapter;
	
	/**
	 * User identity
	 * @var unknown
	 */
	protected $_identity;
	
	protected $_loginRequest;
	
	protected $_trail;
	
	protected $_exceptions = array();
	
	private $_controller;
	
	private $_module;
	
	private $_action;
	
	private $_params;
	
	public function __construct($loginRequest, $adapter, $trail=true, $exceptions = array()){
		$this->_loginRequest = $loginRequest;
		$this->_auth = Zend_Auth::getInstance();
		$this->_adapter = $adapter;
		$this->_trail = $trail;
		$this->_identity = $this->_auth->getIdentity();
		
		if(!empty($exceptions)){
			$this->_exceptions = array_merge($this->_exceptions, $exceptions);
		}
	}
	
	public function dispatchLoopStartup($request){
       	$this->_module = $request->getModuleName();
        $this->_controller = $request->getControllerName();
        $this->_action = $request->getActionName();
        $this->_params = $request->getParams();
	}
	
 	public function preDispatch(Zend_Controller_Request_Abstract $request) {
 		
	    if($this->_controller == 'logout'){//is it a request to logout?
	    	$this->_logout();
	    	$this->_goHome();
		    exit();
    	}
    	
	    if (!$this->_auth->hasIdentity()){
	        Zend_Registry::set('login_form', $this->_getLoginForm());
	        
	    	//is this a login request?
	        if($request->isPost() && $request->getParam('login')){
	        	//authenticate login request
	        	$this->_authenticate($request);
	        }
	        
	        
	        if(!$this->_isLoginRequest()){
	        	$this->pass($request);
	        }
	    }else{
	    	//assertain that the user still has an active account
	    	//$this->_adapter->
	    	$this->assertain();
	    	
    		if($this->_isLoginRequest()){
	    		//trying to login when already logged in
	    		$this->_goHome();
	    	}
	    }
    } 
    
    public function assertain(){
    	if($this->_identity){
    		$users = new Zend_Db_Table('users');
    		
    		$found = $users->find($this->_identity->id);
    		if($found->count()){
    			$user = $found->current();
    			
    			$post = array(
    				'username' => $this->_identity->email,
    				'password' => $user->password
    			);
    			
    			$hash = $this->_createHash($post);
    			
    			if($hash != $this->_identity->hash){
    				$this->_logout();
			    	$this->_goHome();
				    exit();
    			}
    		}
    	}
    }
    
    private function _digest($phrase){
    	return md5($phrase);
    }
    
    private function _isLoginRequest(){
    	return $this->_controller == $this->_loginRequest['controller'] &&
    		$this->_module == $this->_loginRequest['module'] &&
    		$this->_action == $this->_loginRequest['action'];
    }
    
    public function pass($request){
    	$pass = false;
    	
    	foreach ($this->_exceptions as $exception){
        	$eModule = isset($exception['module']) ? $exception['module'] : $this->_module;
        	$eController = isset($exception['controller']) ? $exception['controller'] : $this->_controller;
        	$eAction = isset($exception['action']) ? $exception['action'] : $this->_action;
        		
        	if($eModule == $this->_module &&
        		$eController == $this->_controller &&
        		$eAction == $this->_action
        	){
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

    private function _getLoginForm(){
    	$form = new Zend_Form();
    	
        $form->setDescription('Please authenticate to gain access.');
        
        $form->addElement('text', 'username', array(
            'label' => 'Email',
            'filter' => array(
                'StringTrim'
            ),
            'required' => true
        ))->addElement('password', 'password', array(
            'label' => 'Password',
            'required' => true
        ))->addElement('submit', 'login', array(
            'required' => false,
            'label' => 'Authenticate',
        ));
        
        $form->setDecorators(array(
        	'Description',
        	'FormElements',
        	array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
        	'Form'
        ));
        
        $form->setElementDecorators(array(
        	'ViewHelper',
        	array('HtmlTag', array('tag' => 'dd')),
            array('Label', array('tag' => 'dt'))
        ), array('password', 'username'));
        
        $form->setElementDecorators(array(
        	'ViewHelper',
        	array('HtmlTag', array('tag' => 'dd'))
        ), array('login'));
        
        
        return $form;
    }

    private function _authenticate($request) {
		$this->_auth->setStorage($this->_getAuthStorage());
		    	
    	$post = $request->getPost();
    	
    	$form = Zend_Registry::get('login_form');
    	
    	if($form->isValid($post)){
	        // Get our authentication adapter and check credentials
	        $post['password'] = $this->_digest($post['password']);
    		
	        $adapter = $this->_getAuthAdapter($post);
	        
	        $result = $this->_auth->authenticate($adapter);
	
	        if ($result->isValid()) {
	            $this->_storeIdentity($adapter);
	            
	            $this->_identity->hash = $this->_createHash($post);
	            
		    	if($this->_trail){
			        $this->_trailIdentity();
		    	} 

	            $this->getResponse()->setRedirect($request->getRequestUri());
	        }else{	
	        	$messages = $result->getMessages();
	        	
	        	$form->setDescription(implode('\n', $messages));
    		}
    	}else{
    		$form->setDescription('The authentication credentials you provided are invalid. You can try again.');
    	}	      
    }
    
    private function _createHash($post){
    	$hash = md5($this->_identity->email . $post['password'] . 'secret');
    	return $hash;
    }
    
    private function _storeIdentity($adapter){
    	$storage = $this->_auth->getStorage();

        $this->_identity = $adapter->getResultRowObject(
            null, 'password'
        );
        
        $storage->write($this->_identity);
    }
    
    private function _trailIdentity(){
    	if($this->_trail){
	    	//trail the user
	        $user_trails = new Zend_Db_Table('user_trails');
	        $user_agent = new Zend_Http_UserAgent();
	        $device = $user_agent->getDevice();
	        Zend_Debug::dump($device->getBrowser());
	        $trail = $user_trails->createRow(array(
	        	'session_id' => Zend_Session::getId(),
	          	'ip_address' => $this->getRequest()->getServer('REMOTE_ADDR'),
	           	'user_agent' => $device->getType() . ' (' . $device->getBrowser() . ')',
	          	'login_ts' => mktime(),
	           	'user_id' => $this->_identity->id
	        ));
	            
	        $trail->save();
    	}
    }
 
    private function _getAuthStorage(){
    	return new Zend_Auth_Storage_Session('Zend_Auth');
    } 
    
    private function _getAuthAdapter(array $params) {
        $this->_adapter
                ->setIdentity($params['username'])
                ->setCredential($params['password']);

        return $this->_adapter;
    }
    
    public function requestLogin(){
    	$this->getRequest()
    		->setActionName($this->_loginRequest['action'])
    		->setControllerName($this->_loginRequest['controller'])
    		->setModuleName($this->_loginRequest['module']);
    }
    
    protected function _logout(){
    	$this->_auth->clearIdentity();
    } 
    
    final protected function _forward($action, $controller = null, $module = null, array $params = null)
    {
        $request = $this->getRequest();

        if (null !== $params) {
            $request->setParams($params);
        }

        if (null !== $controller) {
            $request->setControllerName($controller);

            // Module should only be reset if controller has been specified
            if (null !== $module) {
                $request->setModuleName($module);
            }
        }

        $request->setActionName($action)
                ->setDispatched(false);
    }
}