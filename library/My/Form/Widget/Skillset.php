<?php

class My_Form_Widget_Skillset extends Zend_Form_Element_Xhtml{
    protected $_skillsets = array();
    
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'My_Form_Widget_Decorator',
            'My/Form/Widget/Decorator',
            'decorator'
        );
        
        parent::__construct($spec, $options);
    }
    
    public function setSkillsets($value)
    {
        $this->_skillsets = $value;
        return $this;
    }
 
    public function getSkillsets()
    {
        return $this->_skillsets;
    }
 
    public function setValue($value)
    {
        if (is_array($value)) {
            $this->setSkillsets($value);
        } 
 
        return $this;
    }
 
    public function getValue()
    {
        return $this->getSkillsets();
    }
    
    public function isValid($value){
    	$this->setValue($value);
    	if(is_array($value)){
    		foreach ($value as $skillset){
    			if(is_null($skillset['skillset_id']) || empty($skillset['skillset_id'])){
    				return false;
    			}
    			if(is_null($skillset['experience']) || empty($skillset['experience'])){
    				return false;
    			}
    		}
    	}else{
    		return false;
    	}
    	return true;
    }
    
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Skillset')
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