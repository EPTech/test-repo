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
class My_Form_Decorator_Date extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_Date) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $value = $element->getValue() ? $element->getValue() : array();
        $name = $element->getFullyQualifiedName();
        $attribs = $element->getAttribs();
		$ts = $element->getTimestamp();
		
			$date['day'] = $ts ? date('j', $ts) : null;
			$date['month'] = $ts ? date('n', $ts) : null;
			$date['year'] = $ts ? date('Y', $ts) : null;
		
		$days = array(''=>'Day:');
		for($day = 1; $day <= 31; $day++){
			$days[$day] = $day;
		}

		$markup = $view->formSelect($name . '[day]', $date['day'], array(
			'class'=>'input-small'
		), $days);

		$markup .= '&nbsp;';

		$mons = array(''=>'Month:');
		for($mon = 1; $mon <= 12; $mon++){
			$mons[$mon] = date('F', mktime(0,0,0,$mon));
		}

		$markup .= $view->formSelect($name . '[month]', $date['month'], array(
			'class'=>'input-small'
		), $mons);

		$markup .= '&nbsp;';
		
		$markup .= $view->formText($name . '[year]', $date['year'], array(
			'placeholder' => 'Year',
			'size' => 4,
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