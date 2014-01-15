<?php

class My_Form_Element_RefreshableSelect extends Zend_Form_Element_Select{
	protected $_ajaxSource;
	
    public function __construct($spec, $options = null)
    {   
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        
        parent::__construct($spec, $options);
    }
    
    public function setAjaxSource($value){
    	$this->_ajaxSource = $value;
    	return $this;
    }
    
    public function getAjaxSource(){
    	return $this->_ajaxSource;
    }
    
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('RefreshableSelect')
                ->addDecorator('Errors')
                ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                ->addDecorator('HtmlTag', array('tag' => 'dd',
                                                'id'  => $this->getName() . '-element'))
                ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
}