<?php

require_once ('Zend/Form.php');

class My_Form extends Zend_Form {
	
	public function __construct($options=null){
    	$this->addPrefixPath('My_Form_', 'My/Form/');
		parent::__construct($options);
	}
	
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator('Fieldset')
                 ->addDecorator('Description')
                 ->addDecorator('Form');
        }
        
        return $this;
    }	
    
    public function addCompositeElement(array $elements, $name, $label, $order=null){
    	$compositeElement = new My_Form_CompositeElement();
    	
    	$compositeElement->setLabel($label);
    	
    	foreach ($elements as $element){
    		if(!$element instanceof Zend_Form_Element){
    			$element = $this->getElement($element);
    		}
    		
    		$this->removeFromIteration($element->getName());
    		
    		$compositeElement->addElement($element, $element->getName(), $element->getAttribs());
    		
    	}
    	
    	$this->addSubForm($compositeElement, $name, $order);
    	
    	return $this;
    }
    
}