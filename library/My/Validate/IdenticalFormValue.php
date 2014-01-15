<?php
class My_Validate_IdenticalFormValue extends Zend_Validate_Identical
{
	protected $_fieldValue;
	
    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_SAME      => "Passwords do not match",
        self::MISSING_TOKEN => 'No token was provided to match against',
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'token' => '_fieldValue',
    );
	
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if a token has been set and the provided value
     * matches that token.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value, $context=null)
    {
    	$token = $this->getToken();
    	
    	$this->_fieldValue = $context[$token];
    	
        $value = (string) $value;
        $this->_setValue($value);
 		
        if (is_array($context)) {
            if (isset($context[$token])
                && ($value == $context[$token]))
            {
                return true;
            }
        } elseif (is_string($context) && ($value == $context)) {
            return true;
        }
 
        $this->_error(self::NOT_SAME);
        return false;
    }
}