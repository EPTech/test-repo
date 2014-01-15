<?php

class My_Form_Decorator_Submit extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_Submit) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $submitValue = $element->getSubmitValue();
        $applyValue = $element->getApplyValue();
        $resetValue = $element->getResetValue();
        $cancelValue = $element->getCancelValue();
        $cancelUri = $element->getCancelUri();
        $name = $element->getFullyQualifiedName();
 
        $markup = '<div>'
        		. $view->formSubmit($name, $submitValue)
        		. $view->formSubmit($name, $applyValue)
                . $view->formReset($name, $resetValue)
                . '<a href="'.$cancelUri.'">'.$cancelValue.'</a>'
                . '</div>';
 
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
    }
}