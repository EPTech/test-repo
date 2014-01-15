<?php

class My_Form_Element_GeoLocation extends Zend_Form_Element_Xhtml{
	protected $_longitude;
	protected $_latitude;
	
    public function __construct($spec, $options = null)
    {   
        parent::__construct($spec, $options);
        
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
    }
    
    public function setValue($value){
    	//var_dump($value); die;
    	if(is_array($value)){
    		$this->_latitude = $value['latitude'];
    		$this->_longitude = $value['longitude'];
    	}else{
    		throw new Zend_Form_Element_Exception("Invalid value");
    	}
    }
    
    public function getValue(){
    	return array(
    		'longitude' => $this->_longitude,
    		'latitude' => $this->_latitude
    	);
    }
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('GeoLocation')
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