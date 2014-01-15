<?php
class My_Form_Element_File extends Zend_Form_Element_Xhtml 

{
	public $helper = 'formFile';
	
	public function isValid($value, $context = null) 

	{
		
		// for a file upload, the value is not in the POST array, it's in
		// $_FILES
		
		$key = $this->getName ();
		
		if (null === $value) {
			
			if (isset ( $_FILES [$key] )) {
				
				$value = $_FILES [$key];
			
			}
		
		}
		
		$result = parent::isValid ( $value, $context );
		
		Zend_Debug::dump($value); die;
		
		return $result;	
	}

}