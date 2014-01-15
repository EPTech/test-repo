<?php

require_once ('Zend/Rest/Controller.php');

abstract class My_Rest_Controller extends Zend_Rest_Controller {
	
	protected $_response;
	
	public function init(){
		parent::init();
		$this->_response = $this->getResponse();
	}
		
	public function optionsAction(){    
		$this->getResponse()->setBody(null);
        $this->getResponse()->setHeader('Allow', 'OPTIONS, HEAD, INDEX, GET, POST, PUT, DELETE');    
	}

	
	public function headAction(){
		$this->_response->setBody(null);		
	}
}