<?php
class My_Form_Element_Password extends Zend_Form_Element_Password{
	protected $_showMeter = true;
    
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        parent::__construct($spec, $options);
    }
    
    public function getShowMeter(){
    	return $this->_showMeter;
    }
    
    public function setShowMeter($value){
    	$this->_showMeter = $value;
    	return $this;
    }
    
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('ViewHelper')
            	 ->addDecorator('Password')
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array(
                     'tag'   => 'p',
                     'class' => 'description'
                 ))
                 ->addDecorator('HtmlTag', array(
                     'tag' => 'dd',
                     'id'  => $this->getName() . '-element'
                 ))
                 ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
}