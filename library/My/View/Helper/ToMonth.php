<?php
/**
 * ProfileTeaser helper
 * 
 * @author burntblark
 * @version 
 *
 * @uses viewHelper My_View_Helper
 */
class My_View_Helper_ToMonth  extends Zend_View_Helper_Abstract{
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 * 
	 */
	public function toMonth($number) {
		$number = $this->view->escape($number);
		
		return @date('F', mktime(null, null, null, $number));
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}

