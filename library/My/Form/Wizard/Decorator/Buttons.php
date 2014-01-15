<?php

require_once ('Zend/Form/Decorator/Abstract.php');

class My_Form_Wizard_Decorator_Buttons extends Zend_Form_Decorator_Abstract {
public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof My_Form_Wizard_Buttons) {
            // only want to render Date elements
            return $content;
        }

 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
        
        $name  = $element->getFullyQualifiedName();
        
        $markup = '<div name="'.$name.'" class="my-form-wizard-buttons btn-group">';
        
        $backBtn = $view->formSubmit($name, 'Back', array(
        	'disable'=>$element->getBackDisabled(),
			'class' => 'btn'
        ));
        
        $finishBtn = $view->formSubmit($name, 'Finish', array(
        	'disable'=>$element->getFinishDisabled(),
			'class' => 'btn'
        ));
        
        $nextBtn = $view->formSubmit($name, 'Next', array(
        	'disable'=>$element->getNextDisabled(),
			'class' => 'btn'
        ));
        
        $cancelBtn = $view->formSubmit($name, 'Cancel', array(
			'class' => 'btn'
        ));
        
		
		if(!$element->getBackDisabled())
        	$markup .= $backBtn;
		if(!$element->getNextDisabled())
        	$markup .= $nextBtn;
		if(!$element->getFinishDisabled())
        	$markup .= $finishBtn;
        $markup .= $cancelBtn;
        
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