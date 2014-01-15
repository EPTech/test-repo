<?php
/**
 * ProfileTeaser helper
 * 
 * @author burntblark
 * @version 
 *
 * @uses viewHelper My_View_Helper
 */
class My_View_Helper_Identity  extends Zend_View_Helper_Abstract{
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 * 
	 */
	public function identity() {
		$storage = Zend_Registry::get('User_Session');
		return Zend_Auth::getInstance()->setStorage($storage)->getIdentity();
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}

