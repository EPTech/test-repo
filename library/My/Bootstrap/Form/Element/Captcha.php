<?php
class My_Bootstrap_Form_Element_Captcha extends Zend_Form_Element_Captcha
{
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Errors')
                 ->addDecorator('Description', array('tag' => 'span', 'class' => 'help-block'))
                 ->addDecorator(array('controls'=>'HtmlTag'), array('tag'=>'div', 'class'=>'controls'))
                 ->addDecorator('Label', array('class'=>'control-label', 'placement'=>'PREPEND'))
                 ->addDecorator(array('control-group'=>'HtmlTag'), array('tag'=>'div', 'class'=>'control-group'));
        }
        
        return $this;
    }
	
}
