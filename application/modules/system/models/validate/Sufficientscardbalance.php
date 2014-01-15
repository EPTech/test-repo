<?php

class Default_Validate_Sufficientscardbalance extends Zend_Validate_Abstract {

    const SUFFICIENT_BALANCE = 'insufficientBalance';
    const SUFFICIENT_BALANCE2 = 'invalidscardno';
     const SUFFICIENT_BALANCE3 = 'invalidamount';

    protected $_messageTemplates = array(
        self::SUFFICIENT_BALANCE => "Insufficient supercard balance. supercard balance is '%value%'",
        self::SUFFICIENT_BALANCE2 => "Please enter a valid supercard no to debit.",
        self::SUFFICIENT_BALANCE3 => "Invalid amount. Please enter numbers only.",
    );

    public function __construct(Default_Resource_Scardregister $resource) {

        $this->_resource = $resource;
    }

    public function isValid($value, $context = null) {
        if ($value == "") {
            $value = 0;
        }
        $scardno = $context['scardno'];
        if ($scardno == "") {
            $scardno = "";
        }
        
        if(!is_numeric($value)){
             $this->_error(self::SUFFICIENT_BALANCE3);
             return false;
        }
        
        $scard = $this->_resource->getScardregisterByScardno($scardno);
        if ($scard !== null) {
            if ($scard->creditbalance >= $value) {
                return true;
            }
            $this->_setValue($scard->creditbalance);
        }

        if ($scard === null) {
            $this->_error(self::SUFFICIENT_BALANCE2);
        } else {
            $this->_error(self::SUFFICIENT_BALANCE);
        }

        return false;
    }

}
