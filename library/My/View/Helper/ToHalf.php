<?php
/**
 * ProfileTeaser helper
 * 
 * @author burntblark
 * @version 
 *
 * @uses viewHelper My_View_Helper
 */
class My_View_Helper_ToHalf  extends Zend_View_Helper_Abstract{
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	private $_halves = array(
		'1' => 'First Half',
		'2' => 'Second Half'
	);
	
	/**
	 * 
	 */
	public function toHalf($number) {
		$number = $this->view->escape($number);
		
		if(array_key_exists($number, $this->_halves)){
			return $this->_halves[$number];
		}else{
			throw new Zend_Exception('Argument must be between the range of 1 and 2 inclusive');
		}
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}

