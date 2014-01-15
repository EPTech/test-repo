<?php

require_once ('Zend/Form/DisplayGroup.php');

class My_Bootstrap_Form_CompositeElement extends Zend_Form_SubForm {
	protected $_label;
	
	protected $_required;
	
	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return $this;
		}
	
		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this->addDecorator('FormElements')
				->addDecorator(array('controls'=>'HtmlTag'), array('tag' => 'div', 'class' => 'controls'))
				->addDecorator('Label', array('class'=>'control-label'))
				->addDecorator(array('control-group'=>'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'));
		}
		
		return $this;
	}
	
	public function addElement(Zend_Form_Element $element)
	{
		$element->setDecorators(array(
			'ViewHelper',
			array('Label', array('placement'=>'APPEND')),
			'Errors',
			array(array('control'=>'HtmlTag'), array('tag' => 'i', 'style' => 'display: inline-block'))
		));
		parent::addElement($element);
		
	}
	
    public function setLabel($label)
    {
        $this->_label = (string) $label;
        return $this;
    }
    
    public function getLabel()
    {
    	$translator = $this->getTranslator();
    	if (null !== $translator) {
    		return $translator->translate($this->_label);
    	}
    
    	return $this->_label;
    }
    
    public function isRequired()
    {
        return $this->_required;
    }
	
}
