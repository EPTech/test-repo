<?php

class My_Form_Decorator_Password extends Zend_Form_Decorator_ViewHelper{
	public function render($content)
    {
    	$element = $this->getElement();    	

        $view = $element->getView();
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('ViewHelper decorator cannot render without a registered view object');
        }

        $separator     = $this->getSeparator();
        $id            = $element->getId();
        $showMeter 	   = $element->getShowMeter();

        
        if($showMeter){
        	$view->headScript()->appendFile(
        		'/js/jquery.js',
        		'text/javascript'
        	)->appendFile(
        		'/js/pwdmeter.js',
        		'text/javascript'
        	)->appendScript("
        		$(function(){
        			$('#{$id}').pwdmeter();
        		});
        	");
        }
        
        return $content;
    }
}