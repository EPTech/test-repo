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
class My_Form_Decorator_UserPassword extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_UserPassword) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $value = $element->getValue() ? $element->getValue() : array();
        $password = $element->getPassword();
        $confirmation = $element->getConfirmation();
        $name = $element->getFullyQualifiedName();
        $attribs = $element->getAttribs();
		
		$markup = '<div style="display:inline-block;">';
		$markup .= $view->formPassword($name . '[password]', $password);
		$markup .= '<br/><small>Your Password</small>';
		$markup .= '</div>';
		$markup .= '&nbsp;';
		$markup .= '<div style="display:inline-block;">';
		$markup .= $view->formPassword($name . '[confirmation]', $confirmation);
		$markup .= '<br/><small>Re-Type Password</small>';
		$markup .= '</div>';
        
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
	}
}