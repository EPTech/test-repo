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
class My_Form_Decorator_PhoneNumber extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_PhoneNumber) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $value = $element->getValue() ? $element->getValue() : array();
        $ext = $element->getExtension();
        $num = $element->getNumber();
        $name = $element->getFullyQualifiedName();
        $attribs = $element->getAttribs();
		
		$markup = $view->formText($name . '[ext]', $ext, array(
			'size'=>'3',
			'class'=>'input-mini',
			'style' => 'text-align: right;'
		));
		
		$markup .= '-';
		$markup .= $view->formText($name . '[num]', $num, array(
			'class'=>'input-small'
		));
        
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
	}
}