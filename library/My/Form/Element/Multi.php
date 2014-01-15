<?php

class My_Form_Element_Multi extends Zend_Form_Element_Multi{

    protected $_isArray = true;
    public $helper = 'formMultiText';
    
	public function __construct($spec, $options = null){
        $this->addPrefixPath(
            'My_View_Helper',
            'My/View/Helper'
        );
        
		parent::__construct($spec, $options);
	}
}

?>