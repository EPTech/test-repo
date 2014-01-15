<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class My_Service_Response_GlobalPay implements My_Service_Response_Interface{
	protected $_params = array();
	
	public function __construct($response=null) {
		$query = parse_url($response, PHP_URL_QUERY);
		parse_str($query, $this->_params);
	}
	
	public function isSuccessful() {
		if(isset($this->_params['tstatus']) && ($this->_params['tstatus'] == 0)){
			return true;
		}
		
		return false;
	}
	
	public function getParams(){
		return $this->_params;
	}
	
	public function setParams($params){
		$this->_params = $params;
		return $this;
	}
}
