<?php

class My_Form_Decorator_RandGenerator extends Zend_Form_Decorator_ViewHelper{
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

        $view->headScript()->appendFile(
        	'/js/jquery.js',
        	'text/javascript'
        )->appendScript("
        	$(function(){
        		$.get('/async/rand-tax-id', {}, function(response){
        			$('#{$id}').val(response);
        		});
        	});
        ");
        
        return $content;
    }
}