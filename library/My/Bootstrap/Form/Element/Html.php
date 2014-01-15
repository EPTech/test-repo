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
class My_Form_Element_Html extends Zend_Form_Element_Hidden{
	protected $_html;
	
	public function getHtml(){
		return $this->_html;
	}
	
	public function setHtml($value){
		$this->_html = $value;
		return $this;
	}
	
	public function loadDefaultDecorators(){
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator(new My_Form_Decorator_Html())
	             ->addDecorator(array('controls'=>'HtmlTag'), array(
					 'tag'=>'div', 'class'=>'controls',
					 'id'  => $this->getName() . '-element'
				 ))->addDecorator('Label', array('class'=>'control-label', 'placement'=>'PREPEND'))
                 ->addDecorator(array('control-group'=>'HtmlTag'), array('tag'=>'div', 'class'=>'control-group'));
        }
        
        return $this;
    }
}
