<?php
class My_Bootstrap_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
{
    /**
     * Load default decorators
     *
     * Disables "for" attribute of label if label decorator enabled.
     *
     * @return Zend_Form_Element_Radio
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }
        
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
        	$this->addDecorator('ViewHelper')
                 ->addDecorator('Label', array('tag'=>'label', 'class'=>'checkbox'))
                 ->addDecorator(array('controls'=>'HtmlTag'), array('tag'=>'div', 'class'=>'controls'))
                 ->addDecorator(array('control-group'=>'HtmlTag'), array('tag'=>'div', 'class'=>'control-group'));
        	
        }
        
        return $this;
    }
}
