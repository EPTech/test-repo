<?php
/**
 * ProfileTeaser helper
 * 
 * @author burntblark
 * @version 
 *
 * @uses viewHelper My_View_Helper
 */
class My_View_Helper_ToQuarter  extends Zend_View_Helper_Abstract{
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	private $_quarters = array(
		'1' => 'First Quarter',
		'2' => 'Second Quarter',
		'3' => 'Third Quarter',
		'4' => 'Fourth Quarter',
	);
	
	/**
	 * 
	 */
	public function toQuarter($number) {
		$number = $this->view->escape($number);
		
		if(array_key_exists($number, $this->_quarters)){
			return $this->_quarters[$number];
		}else{
			throw new Zend_Exception('Argument must be between the range of 1 and 4 inclusive');
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

