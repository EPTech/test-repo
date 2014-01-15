<?php
class My_Bootstrap_Form_Element_Radio extends Zend_Form_Element_Radio
{
	/**
     * Use formRadio view helper by default
     * @var string
     */
    public $helper = 'bootstrapRadio';

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
        	->addDecorator('Errors') 
            ->addDecorator('Description', array('tag' => 'span', 'class' => 'help-block'))
            ->addDecorator(array('controls'=>'HtmlTag'), array('tag'=>'div', 'class'=>'controls'))
            ->addDecorator('Label', array('class'=>'control-label', 'placement'=>'PREPEND'))
            ->addDecorator(array('control-group'=>'HtmlTag'), array('tag'=>'div', 'class'=>'control-group'));
        	
        }
        
        return $this;
    }
}
