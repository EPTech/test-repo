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
class My_Form_Element_UserPassword extends Zend_Form_Element_Password{	
	protected $_password;
	
	protected $_confirmation;
	
	public function getValue(){
		return $this->_password;
	}
	
	public function setPassword($value){
		$this->_password = $value;
		return $this;
	}
	
	public function setConfirmation($value){
		$this->_confirmation = $value;
		return $this;
	}
	
	public function getPassword(){
		return $this->_password;
	}
	
	public function getConfirmation(){
		return $this->_confirmation;
	}
	
	public function setValue($value){
		if(is_array($value)){
			$this->_password = isset($value['password']) ? $value['password'] : null;
			$this->_confirmation = isset($value['confirmation']) ? $value['confirmation'] : null;
		}
	}
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator(new My_Form_Decorator_UserPassword())
                	->addDecorator('Errors')
                	->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
	                 ->addDecorator('HtmlTag', array(
	                     'tag' => 'dd',
	                     'id'  => $this->getName() . '-element'
	                 ))
	                 ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
	
	public function isValid($value, $context = null) {
		if(parent::isValid($value, $context)){
			if($this->getConfirmation() == $this->getPassword()){
				return true;
			}else{
				$this->addError('Your password confirmation does not match');
			}
		}
		
		return false;
	}
}
