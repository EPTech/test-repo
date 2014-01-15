<?php

class My_Form_Element_Camera extends Zend_Form_Element_Xhtml{
	
    public function __construct($spec, $options = null)
    {   
        parent::__construct($spec, $options);
        
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
    }
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Camera')
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