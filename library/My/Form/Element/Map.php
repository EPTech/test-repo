<?php

class My_Form_Element_Map extends Zend_Form_Element_Text{
    public function __construct($spec, $options = null)
    {   
        parent::__construct($spec, $options);
        
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        
        //$this->setAttrib('readonly', true);
    }
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('ViewHelper')
                	->addDecorator('Map')
                	->addDecorator('Errors')
                	->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
	                 ->addDecorator('HtmlTag', array(
	                     'tag' => 'dd',
	                     'id'  => $this->getName() . '-element'
	                 ))
	                 ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
}