<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sms
 *
 * @author burntblark
 */
class My_Sms {

	const DEFAULT_LENGHT = 160;
    protected $_sender = null;
    protected $_recipients = array();
    protected $_message;

    public function __construct($sid, $message="") {
        $this->_sender = $sid;
        $this->_message = $message;
    }

    public function addRecipient($recipient) {
        if (preg_match('/^0[7-9][01]\d{8}$/', $recipient)) {
            $recipient = preg_replace('/^0/', '+234', $recipient);
            //die($recipient);
        }
        
        $this->_recipients[] = $recipient;
    }

    public function getMessage(){
        return $this->_message;
    }
    
    public function setMessage($message){
        $this->_message = $message;
        return $this;
    }

    public function setRecipients($recipients, $delimiter=',') {
    	unset($this->_recipients);
    	$this->addRecipients($recipients, $delimiter);
        return $this;
    }

    public function setRecipient($recipients) {
    	unset($this->_recipients);
    	$this->addRecipient($recipients);
        return $this;
    }
    
    public function addRecipients($recipients, $delimiter=',') {
    	if(is_string($recipients)){
    		$recipients = explode($delimiter, $recipients);
    	}
    	
    	if(is_array($recipients)){
    		foreach ($recipients as $recipient) {
    			$this->addRecipient($recipient);
    		}
    	}
    	
        return $this;
    }
    
    public function getRecipients() {
        return $this->_recipients;
    }

    public function getSenderId() {
        return $this->_sender;
    }

    public function isLong(){
    	if(strlen($this->_message) > self::DEFAULT_LENGHT){
    		return 1;
    	}else{
    		return 0;
    	}
    }
    
    public function send(Zend_Http_Client $gateway){
    	$recipients = implode(',', $this->_recipients);
    	
	    $params = array(
	        'UN' => 'eyobassey',
	        'p' => 'Kumasi43',
	        'SA' => $this->_sender,
	        'DA' => $recipients,
	        'L' => $this->isLong(),
	        'M' => $this->_message
	    );
	      
	    $data = http_build_query($params);
	    
		$gateway->setRawData($data);

		try{
			$response = $gateway->request();
			
			if(strpos($response->getBody(), 'OK')){
				return true;
			}
		}catch(Zend_Http_Client $ex){
			throw new My_Sms_Exception($ex->getMessage());
		}catch(Zend_Http_Client_Adapter_Exception $ex){
			throw new My_Sms_Exception($ex->getMessage());
		}	
    }
}