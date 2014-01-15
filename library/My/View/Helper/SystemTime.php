<?php

class My_View_Helper_SystemTime extends Zend_View_Helper_Abstract {

    public function systemTime($value) {
    	Zend_Date::setOptions(array(
    		'format_type'=>'php'
    	));
    	
        $date = new Zend_Date($value);
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        //include the following line in your application.ini config
        //system.time.format
        $format = isset($config->system->time->format) ? $config->system->time->format : 'h:i:s A';
        
        return $date->get($format);
    }
}
