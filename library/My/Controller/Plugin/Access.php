<?php

require_once 'Zend/Controller/Plugin/Abstract.php';

class My_Controller_Plugin_Access extends Zend_Controller_Plugin_Abstract {
	protected $_acl;
	
	public function __construct(Zend_Acl $acl=null){
		$this->_acl = $acl;
	}
	
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
    	$actor = Zend_Auth::getInstance()->getIdentity();
    	
    	$resourceId = str_replace('-', '', $request->getModuleName()) .':'. 
    		str_replace('-', '', $request->getControllerName());
    		
    	
    	if(is_null($this->_acl) || !$this->_acl->has($resourceId)){
    		return;
    	}
    	
    	if(!$this->_acl->isAllowed($actor->role_id, $resourceId)){ 
        	$layout = Zend_Layout::getMvcInstance();
        	$layout->setLayout('empty');
    		$request->setDispatched(true);
    	}
    }

}