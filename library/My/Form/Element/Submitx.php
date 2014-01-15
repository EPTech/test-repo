<?php

class My_Form_Element_Submit extends Zend_Form_Element_Xhtml{
	protected $_submitValue;
	protected $_applyValue;
	protected $_resetValue;
	protected $_cancelValue;
	protected $_cancelUri;
	
	
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        
		$this->_submitValue = 'Save & Exit';
		$this->_applyValue = 'Save';
		$this->_resetValue = 'Reset';
		$this->_cancelValue = 'Cancel';
		
        parent::__construct($spec, $options);
    }
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Submit')
            		->addDecorator('DtDdWrapper');
        }
    }
    
	public function setSubmitValue($label = null){
		if(!is_null($label)){
			$this->_submitValue = $label;
		}
	}
	
	public function setApplyValue( $label = null){
		if(!is_null($label)){
			$this->_applyValue = $label;
		}
	}
	
	public function setResetValue( $label = null){
		if(!is_null($label)){
			$this->_resetValue = $label;
		}
	}
	
	public function getSubmitValue(){
		return $this->_submitValue;
	}
	
	public function getApplyValue(){
		return $this->_applyValue;
	}
	
	public function getResetValue(){
		return $this->_resetValue;
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
			$this->setSubmitValue($labels['submit']);
			$this->setApplyValue($labels['apply']);
			$this->setResetValue($labels['reset']);
			$this->setCancelValue($labels['cancel']);
		}
		
		return $this;
	}
}