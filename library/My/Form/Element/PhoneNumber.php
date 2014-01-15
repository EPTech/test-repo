<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhoneNumber
 *
 * @author TUNDE
 */
class My_Form_Element_PhoneNumber extends Zend_Form_Element_Xhtml{
	protected $_ext;
	
	protected $_num;
	
	public function getValue(){
		return $this->_ext . $this->_num;
	}
	
	public function setExtension($value){
		$this->_ext = $value;
		return $this;
	}
	
	public function getExtension(){
		return $this->_ext;
	}
	
	public function setNumber($value){
		$this->_num = $value;
		return $this;
	}
	
	public function getNumber(){
		return $this->_num;
	}
	
	public function setValue($value){
		if(is_string($value)){
			$ext = $this->getExtension();
			
			if(is_null($ext)){
				throw new Zend_Form_Element_Exception('Extension cannot be null');
			}
			
			$this->setNumber(preg_filter("/^\\".$ext."/", "", $value));
		}elseif(is_array($value)){
			$this->setExtension($value['ext']);
			$this->setNumber($value['num']);
		}
		
		return $this;
	}
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator(new My_Form_Decorator_PhoneNumber())
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
