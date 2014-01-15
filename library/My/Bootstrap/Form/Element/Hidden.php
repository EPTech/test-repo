<?php
class My_Bootstrap_Form_Element_Hidden extends Zend_Form_Element_Hidden
{
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('ViewHelper');
        }
        
        return $this;
    }
	
}
