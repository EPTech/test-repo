<?php

class My_Form_Element_Confirm extends Zend_Form_Element_Xhtml{
	protected $_okayValue;
	protected $_cancelValue;
	protected $_cancelUri;
	
	
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        
		$this->_okayValue = 'Continue';
		$this->_cancelValue = 'Cancel';
		
        parent::__construct($spec, $options);
    }
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Confirm')
            		->addDecorator('DtDdWrapper');
        }
    }
    
	public function setOkayValue($label = null){
		if(!is_null($label)){
			$this->_okayValue = $label;
		}
	}
	
	public function getOkayValue(){
		return $this->_okayValue;
	}

	public function setCancelValue($label = null){
		if(!is_null($label)){
			$this->_cancelValue = $label;
		}
	}
	
	public function getCancelValue(){
		return $this->_cancelValue;
	}

	public function setCancelUri($uri){
		$this->_cancelUri = $uri;
		return $this;
	}
	
	public function getCancelUri(){
		return $this->_cancelUri;
	}
	
	public function setValue($labels){
		if(is_array($labels)){
			$this->setSubmitValue($labels['okay']);
			$this->setCancelValue($labels['cancel']);
		}
		
		return $this;
	}
}