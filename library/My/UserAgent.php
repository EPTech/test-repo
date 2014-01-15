<?php
class My_UserAgent {
	
	public static function getUserAgent() {
		$userAgent = new Zend_Http_UserAgent ();
		$device = $userAgent->getDevice ();
		$typeBrowser = $device->getType () . ' ' . $device->getBrowser ();
		return $typeBrowser;
	}
	
	public static function getRemoteAddr(){
		return $_SERVER['REMOTE_ADDR'];
	}
}