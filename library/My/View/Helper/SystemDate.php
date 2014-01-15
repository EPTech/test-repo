<?php

class My_View_Helper_SystemDate extends Zend_View_Helper_Abstract {

    public function systemDate($value=null) {
    	Zend_Date::setOptions(array(
    		'format_type'=>'php'
    	));
    	
        $date = new Zend_Date($value);
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $format = $config->system->date->format;
        $format = $format ? $format : null;
        
        return $date->get($format);
    }
}
