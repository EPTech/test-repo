<?php

class System_Validate_PlatePattern extends Zend_Validate_Abstract {

    const INVALID_PATTERN = 'invalid Pattern';

    protected $_messageTemplates = array(
        self::INVALID_PATTERN => 'Invalid platenumber pattern. please enter in the following format RSH-234AP',
    );

   

    public function isValid($value, $context = null) {
        $pattern = "/^[a-zA-Z]{3}-[0-9]{2,3}[a-zA-Z]{2}$/";
        if (preg_match($pattern, $value)) {
            return true;
        }
        $this->_error(self::INVALID_PATTERN);
        return false;
    }

}
