<?php
require_once 'Zend/Controller/Plugin/Abstract.php';

class My_Controller_Plugin_AuthWithOffice extends My_Controller_Plugin_Auth {
	
 	public function preDispatch(Zend_Controller_Request_Abstract $request) {
       	$_module = $request->getModuleName();
        $_controller = $request->getControllerName();
        $_action = $request->getActionName();
        $_params = $request->getParams();
 	
    	
        parent::preDispatch($request);
 	
        if($this->_auth->hasIdentity()){
        	$parentStorage = $this->_auth->getStorage();
        	
	        $this->_auth->setStorage($this->_getAuthStorage());
        	    	
	        if (!$this->_auth->hasIdentity()){
		        Zend_Registry::set('login_form', $this->_getLoginForm());
		        
		    	//is this a login request?
		        if($request->isPost() && $request->getParam('login_office')){
		        	//authenticate login request
		        	$this->_authenticate($request);
		        }
		        
		        $this->pass($request);
		        
		        if(!$this->_auth->hasIdentity() && ($_controller != 'login')){	        		
				    //$this->requestLogin();				    
				    return;
		    	}
		    }
        }else{
	        $this->_auth->setStorage($this->_getAuthStorage());
	        $this->_logout();
        }
    } 

    private function _getLoginForm(){
    	$form = new Zend_Form();
    	
        $form->setDescription('Please authenticate to gain access.');
        
        $form->addElement('hidden', 'office_id', array(
            'filter' => array(
                'StringTrim'
            ),
            'value' => $this->_identity->id,
            'required' => true,
            'decorators' => array(
            	'ViewHelper'
            )
        ))->addElement('password', 'password', array(
            'label' => 'Password',
            'required' => true
        ))->addElement('submit', 'login_office', array(
            'required' => false,
            'label' => 'Let me in!',
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
        ), array('password', 'office_id'));
        
        $form->setElementDecorators(array(
        	'ViewHelper',
        	array('HtmlTag', array('tag' => 'dd'))
        ), array('login_office'));
        
        
        return $form;
    }

    protected function _authenticate($request) {
		$this->_auth->setStorage($this->_getAuthStorage());
		    	
    	$post = $request->getPost();
    	
    	$form = Zend_Registry::get('login_form');
    	
    	if($form->isValid($post)){
	        // Get our authentication adapter and check credentials
	        $adapter = $this->_getAuthAdapter($post);
	
	        $result = $this->_auth->authenticate($adapter);
	
	        if ($result->isValid()) {
	            
	            $this->_storeIdentity($adapter);

	            $this->getResponse()->setRedirect($request->getRequestUri());
	        }else{
	        	
	        }
    	}else{
    		$form->setDescription('The authentication credentials you provided are invalid. You can try again.');
    	}	      
    }
 
    protected function _getAuthStorage(){
    	return new Zend_Auth_Storage_Session('Office_Auth');
    } 
    
    private function _storeIdentity($adapter){
    	$storage = $this->_auth->getStorage();

        $this->_identity = $adapter->getResultRowObject(
            null, 'password'
        );
        
        $storage->write($this->_identity);
    }
    
    protected function _getAuthAdapter(array $params) {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();

        $authAdapter = new Zend_Auth_Adapter_DbTable(
                        $dbAdapter,
                        'offices',
                        'id',
                        'password',
                        'MD5(?)'
        );

        $authAdapter
                ->setIdentity($params['office_id'])
                ->setCredential($params['password']);

        return $authAdapter;
    } 
}