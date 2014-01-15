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

class My_Form_Element_Date extends Zend_Form_Element {
    
	protected $_ts;
	
	protected $_format = 'Y-m-d';
	
	public function getValue(){
		if(!is_null($this->_ts)){
			return date($this->_format, $this->_ts);
		}
	}
	
	public function setTimestamp($value){
		$this->_ts = $value;
		return $this;
	}
	
	public function setFormat($value){
		$this->_format = $value;
		return $this;
	}
	
	public function getTimestamp(){
		return $this->_ts;
	}
	
	public function setValue($value){
		if(is_array($value)){
			$day = isset($value['day']) ? $value['day'] : null;
			$month = isset($value['month']) ? $value['month'] : null;
			$year = isset($value['year']) ? $value['year'] : null;
		
			$date = $year . '-' . $month . '-' . $day;
		}elseif(is_string($value)){
			$date = $value;
		}
		
		if(Zend_Date::isDate($date, $this->_format)){
			$this->_ts = strtotime($date);
			return $this;
		}
	}
    
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator(new My_Form_Decorator_Date())
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
