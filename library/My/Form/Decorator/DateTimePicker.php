<?php

class My_Form_Decorator_DateTimePicker extends Zend_Form_Decorator_ViewHelper{
	public function render($content)
    {
    	$element = $this->getElement();
	
    	if(!($element instanceof My_Form_Element_DateTimePicker)){
    		return;
    	}
    	
        $view = $element->getView();
        
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('ViewHelper decorator cannot render without a registered view object');
        }
        
        $name = $element->getName();
        $date = $element->getDate();
        $time = $element->getTime();
                
        $elementContent = $view->formText($name.'[date]', $date, array('class'=>'datepicker', 'readonly'=>true))
        				. ' ' . $view->formText($name.'[time]', $time, array('class'=>'timepicker', 'readonly'=>true));
        				
        $view->headScript()->appendFile('/js/jquery.js')
        ->appendFile('/js/jquery-ui-1.8.9.custom.min.js')
        ->appendFile('/js/jquery.ui.timepicker-0.0.8.js')
        ->appendScript('
    		$(function(){
    			$(".datepicker").datepicker({
			    	inline: true,
					dateFormat: "yy-mm-dd",
					changeYear: true,
					constrainInput: true
    			});
    			
				$(".timepicker").timepicker({
				    showPeriod: true,
				    showLeadingZero: true
				});
    		});
    	');
		
		$view->headLink()->appendStylesheet('/styles/jquery-ui-1.8.7.custom.css')
						 ->appendStylesheet('/styles/jquery-ui-timepicker.css')
						 ->appendStylesheet('/styles/datetimepicker.css');
        	
        $view->headScript();
        
        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $elementContent;
            case self::PREPEND:
                return $elementContent . $content;
            default:
                return $elementContent;
        }
    }
}