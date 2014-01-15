<?php
class My_Validate_PasswordExists extends Zend_Validate_Abstract
{
	const NOT_EXIST = 'notExist';
	/**
	 * 
	 * @var mixed
	 */
	protected $_checker;
	
    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EXIST      => "The password you entered is not correct"
    );
	
	public function __construct($checker){
		$this->_checker = $checker;
	}
	
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if a token has been set and the provided value
     * matches that token.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
    	$valid = false;
    	
    	$this->_setValue($value);
    	
    	if(is_callable($this->_checker)){
    		$valid = call_user_func($this->_checker, $value);
    	}else{
    		throw new Exception(); //TODO: create exception class
    	}
    	
    	if($valid){
    		return true;
    	}
    	
    	$this->_error(self::NOT_EXIST);
    	return $valid;
    }
}