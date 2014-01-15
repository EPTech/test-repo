<?php

class My_Form_Element_EditableSelect extends Zend_Form_Element_Select{
	protected $_addUrl;
	protected $_editUrl;
	
    public function __construct($spec, $options = null)
    {   
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        
        parent::__construct($spec, $options);
    }

    public function setAddUrl($value){
    	$this->_addUrl = $value;
    	
    	return $this;
    }
    
    public function setEditUrl($value){
    	$this->_editUrl = $value;
    	
    	return $this;
    }

    public function getAddUrl(){
    	return $this->_addUrl;
    }
    
    public function getEditUrl(){
    	return $this->_editUrl;
    }
    
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('EditableSelect')
                ->addDecorator('Errors')
                ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                ->addDecorator('HtmlTag', array('tag' => 'dd',
                                                'id'  => $this->getName() . '-element'))
                ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
}