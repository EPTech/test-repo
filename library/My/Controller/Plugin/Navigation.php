<?php

require_once 'Zend/Controller/Plugin/Abstract.php';

class My_Controller_Plugin_Navigation extends Zend_Controller_Plugin_Abstract {

    private $_view;
    protected $_acl;
    protected $_auth;
    protected $_config;

    public function __construct(Zend_View_Abstract $view, Zend_Config_Xml $config, Zend_Acl $acl = null) {
        $this->_view = $view;
        $this->_acl = $acl;
        $this->_config = $config;
        $this->_auth = Zend_Auth::getInstance();
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request) {
        $viewNav = $this->_view->navigation()->addPages($this->_config);
        	//die($this->_view->navigation()->menu()); die;
        if ($this->_auth->hasIdentity()) {
        	$identity = $this->_auth->getIdentity();
        	
        	if(!is_null($this->_acl)){
        		
	            $viewNav
	            	->setAcl($this->_acl)
	            	->setRole(
	            		$identity->role_id
	            	);            	
        	}
        }
            	
       if(Zend_Registry::isRegistered('TranslationObject')){
     		$viewNav->setTranslator(Zend_Registry::get('TranslationObject'))->setInjectTranslator(true);
       }
    }
    
    

}