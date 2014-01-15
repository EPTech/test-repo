<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PayporteWallet
 *
 * @author TUNDE
 */
class My_Service_Response_NIBBS implements My_Service_Response_Interface {
	protected $_params = array();
	
	public function __construct($response=null) {
		if(!is_null($response)){
			$query = parse_url($response, PHP_URL_QUERY);
			parse_str($query, $this->_params);
		}
	}
	
	public function isSuccessful() {
		//if(isset($this->_params['tstatus']) && ($this->_params['tstatus'] == 0)){
			return true;
	//	}
		
		//return false;
	}
	
	public function getParams(){
		return $this->_params;
	}
	
	public function setParams($params){
		$this->_params = $params;
		return $this;
	}
}

?>
