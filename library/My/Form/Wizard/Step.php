<?php

require_once ('Zend/Form/SubForm.php');

class My_Form_Wizard_Step extends Zend_Form_SubForm {
	protected $_context;
	
	public function __construct(Zend_Session_Namespace $context=null, $options=array()){
		$this->_context = $context;
		
        if (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options instanceof Zend_Config) {
            $this->setConfig($options);
        }
        
        $this->init();

        //$this->loadDefaultDecorators();
        //$this->setDecorators(array());
	}
	
	public function setContext(Zend_Session_Namespace $context){
		$this->_context = $context;
	}
	
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }
        $decorators = $this->getDecorators();
        
        if (empty($decorators)) {
			$this->setDecorators(array(
	            'FormElements',
	            array('HtmlTag', array(
	            	'tag' => 'dl',
	                'class' => 'zend_form'
	            )), 
	        ));
        }
	}
	
	public function isValid($data){
		//once data is valid, store data in session
		if(parent::isValid($data)){
			$name = $this->getName();
			$values = $this->getValues();
            $this->_context->data[$name] = $values[$name];
			//Zend_Debug::dump($this->_context->data); die;
            return true;
		}
		
		return false;
	}
}