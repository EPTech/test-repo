<?php

class My_Form_Decorator_FingerPrintCapture extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_FingerPrintCapture) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $id = $element->getId();
        $value = $element->getValue();
        $name = $element->getFullyQualifiedName();
        $attribs = $element->getAttribs();
       
        $markup = '<div id="'.$id.'"><iframe style="border:none" width="670" height="690" src="/FormMain.php"></iframe>
        </div>';
           
        //set styles and scripts
        $view->headScript()->appendFile(
        	'/js/jquery.js',
        	'text/javascript'
        )->appendScript("
        	$(function(){
    		});
        ");
        
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
    }
}