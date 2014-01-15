<?php

class My_Validate_PercentageShare extends Zend_Validate_Abstract{
	const LIMIT_EXCEEDED = 'limitExceeded';
	const LIMIT_UNREACHED = 'limitUnreached';

   	protected $_messageTemplates = array(
     	self::LIMIT_EXCEEDED => 'Percentage limit exceeded. Total = %value%',
     	self::LIMIT_UNREACHED => 'Total percentage less than 100. Total = %value%'
   	);
   
	public function isValid($value, $context=null) {
		$rTotal = null;
		
	    $value = (string) $value;
	    $this->_setValue($value);
    
		foreach($context as $share){
			$rTotal += (int) $share;
		}
		
		$total = $rTotal;
		
		if($total > 100){
			$this->_error(self::LIMIT_EXCEEDED, $total);
			return false;
		}
		
		if($total < 100){
			$this->_error(self::LIMIT_UNREACHED, $total);
			return false;
		}
		
		return true;
	}


}

?>