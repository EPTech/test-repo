<?php

require_once ('Zend/Form/DisplayGroup.php');

class My_Bootstrap_Form_Actions extends Zend_Form_DisplayGroup {
	
	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return $this;
		}
	
		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this->addDecorator('FormElements')
				->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'form-actions'));
		}
		
		return $this;
	}
	
}
