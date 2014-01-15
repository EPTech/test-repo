<?php

class My_View_Helper_Age extends Zend_View_Helper_Abstract{
    public static function calculateAge($birth, $death = null)
    {
    	Zend_Date::setOptions(array(
    		'format_type'=>'php'
    	));
    	
    	//Zend_Debug::dump(Zend_Date::isDate($birth, 'Y-m-d')); die;
        $birthDate = new Zend_Date($birth);
        if (!Zend_Date::isDate ($birthDate)){
           $birthDate = new Zend_Date();
        }
           
        $deathDate = new Zend_Date($death);
        if (!Zend_Date::isDate ($deathDate)){
           $deathDate = new Zend_Date();
        }
        
        $dYear  = $deathDate->get('Y') - $birthDate->get('Y');
        $dMonth = $deathDate->get('m') - $birthDate->get('m');
        $dDay   = $deathDate->get('d') - $birthDate->get('d');
        
        if ($dMonth < 0){
           $dYear--;
        }elseif (($dMonth==0) && ($dDay < 0)) $dYear--;
        
        return $dYear;
    }
    
    public function age($birth, $death = null)
    {
        return $this->calculateAge($birth, $death);
    }
	
}
