<?php

class My_Form_Wizard_Buttons extends Zend_Form_Element_Xhtml{
	protected $_backDisabled = false;
	protected $_nextDisabled = false;
	protected $_finishDisabled = true;
	
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'My_Form_Wizard_Decorator',
            'My/Form/Wizard/Decorator',
            'decorator'
        );
		
        parent::__construct($spec, $options);
    }
    
    public function getBackDisabled(){
    	return $this->_backDisabled;
    }
    
    public function setBackDisabled($flag=false){
    	$this->_backDisabled = $flag;
    	return $this;
    }
    
    public function getNextDisabled(){
    	return $this->_nextDisabled;
    }
    
    public function setNextDisabled($flag=false){
    	$this->_nextDisabled = $flag;
    	return $this;
    }
    
    public function getFinishDisabled(){
    	return $this->_finishDisabled;
    }
    
    public function setFinishDisabled($flag=false){
    	$this->_finishDisabled = $flag;
    	return $this;
    }
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Buttons')
            		->addDecorator('DtDdWrapper');
        }
    }
}