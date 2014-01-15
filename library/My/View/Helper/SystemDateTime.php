<?php

class My_View_Helper_SystemDateTime extends Zend_View_Helper_Abstract {

    public function systemDateTime($value=null) {
    	Zend_Date::setOptions(array(
    		'format_type'=>'php'
    	));
    	
        $date = new Zend_Date($value);
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $format = $config->system->date->format . ' ' . $config->system->time->format;
        $format = $format ? $format : null;
        
        return $date->get($format);
    }
}
