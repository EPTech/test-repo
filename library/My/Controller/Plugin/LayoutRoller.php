<?php

require_once 'Zend/Controller/Plugin/Abstract.php';

class My_Controller_Plugin_LayoutRoller extends Zend_Controller_Plugin_Abstract{
	
	protected $_config = array();
	
	public function __construct($config = array()){
		if(!empty($config)){
			$this->_config = array_merge($this->_config, $config);
		}
	}
	
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$rModule = $request->getModuleName();
    	$controller = $request->getControllerName();
    	$action = $request->getActionName();
        $layoutIns = Zend_Layout::getMvcInstance();
    	
		
		$layout = isset($this->_config[$rModule]) ? $this->_config[$rModule] : 'layout';
		
		if(is_array($layout)){
			$layout = isset($layout[$controller]) ? $layout[$controller] : 'layout';
			
			if(is_array($layout)){
				$layout = isset($layout[$action]) ? $layout[$action] : 'layout';
			}
		}
		
		$layoutIns->setLayout($layout);
    }
}