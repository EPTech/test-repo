<?php

require_once ('Zend/Form.php');

class My_Bootstrap_Form extends Zend_Form {
    /**
     * Default display group class
     * @var string
     */
    
    protected $_defaultDisplayGroupClass = 'My_Bootstrap_Form_DisplayGroup';
	
	public function __construct($options=null){
    	$this->addPrefixPath('My_Form_', 'My/Form/');
    	$this->addPrefixPath('My_Bootstrap_Form_', 'My/Bootstrap/Form/');
		parent::__construct($options);
	}
	
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Description')
                 ->addDecorator('FormElements')
                 ->addDecorator('Fieldset')
                 ->addDecorator('Form');
        }
        
        return $this;
    }
    
    public function enableBootrapElements(){
    	$this->addPrefixPath('My_Bootstrap_Form_', 'My/Bootstrap/Form/');
    	return $this;
    }
    
    public function addFormActions(array $elements){
    	$this->addDisplayGroup($elements, 'form-actions', array(
    		'displayGroupClass' => 'My_Bootstrap_Form_Actions',
    		'order' => 9999999999
    	));
    	
    	return $this;
    }
    
    public function addCompositeElement(array $elements, $name, $label, $order=null){
    	$compositeElement = new My_Bootstrap_Form_CompositeElement();
    	
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
    
    public function init(){
        parent::init();
        $this->setAttrib("class", "form-horizontal");
    }
    
    
}