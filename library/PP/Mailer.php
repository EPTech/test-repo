<?php
Class PP_Mailer
{

	protected $_appConfig;
	
	protected function _getGMailerConfig()
	{
		if(is_null($this->_appConfig)){
			$this->_appConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
		}
		return $this->_appConfig->gmailer;
	}
	
	public function mail($email, $contents, $subject, $templateMap = false){	
		$mailerConfig = $this->_getGMailerConfig();
		require_once('phpgmailer/class.phpgmailer.php');
		$mail = new PHPGMailer();
		
		if(is_array($templateMap)){
			$fp = fopen($contents, 'rb');
			$contents = stream_get_contents($fp);
			fclose($fp);
			$search = array_keys($templateMap);
			
			$contents = str_replace($search, $templateMap, $contents);
		}
		
		$mail->Username = $mailerConfig->username;
		$mail->Password = $mailerConfig->password;
		$mail->From = $mailerConfig->from;
		$mail->FromName =  $mailerConfig->fromname;
		$mail->Subject = $subject;
		$mail->AddAddress($email);
		$mail->Body = $contents;
		$mail->IsHTML(true);
	
		return $mail->Send();
	}

}