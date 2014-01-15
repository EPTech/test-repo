<?php
class My_Controller_Plugin_RestHandler extends Zend_Controller_Plugin_Abstract
{
	private $availableMimeTypes = array(
		'php'           => 'text/php',
		'xml'           => 'application/xml',
		'json'          => 'application/json',
		'amf'           => 'application/octet-stream',
		'urlencoded'    => 'application/x-www-form-urlencoded'
	
	);
 
	private $methods = array('OPTIONS', 'HEAD', 'INDEX', 'GET', 'POST', 'PUT', 'DELETE');
	 
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
	    if (!in_array(strtoupper($request->getMethod()), $this->methods))
	    {
	        $request->setActionName('options');
	        $request->setDispatched(true);
	 
	        $this->getResponse()->setHttpResponseCode(405);
	 
	        return;
	    }
	    else
	    {
	        $contentType = $this->getMimeType($request->getHeader('Content-Type'));
	        $rawBody = $request->getRawBody();
	 
	        if (!empty($rawBody))
	        {
	            try
	            {
	                switch ($contentType)
	                {
	                    case 'application/json':
	                        $params = Zend_Json::decode($rawBody);
	                        break;
	 
	                    case 'text/xml':
	                    case 'application/xml':
	                        $json = Zend_Json::fromXml($rawBody);
	                        $params = Zend_Json::decode($json, Zend_Json::TYPE_OBJECT)->request;
	                        break;
	 
	                    case 'application/octet-stream':
	                        $serializer = new Zend_Serializer_Adapter_Amf3();
	                        $params = $serializer->unserialize($rawBody);
	                        break;
	 
	                    case 'text/php':
	                        $params = unserialize($rawBody);
	                        break;
	 
	                    case 'application/x-www-form-urlencoded':
	                        $params = array();
	                        parse_str($rawBody, $params);
	                        break;
	 
	                    default:
	                        $params = $rawBody;
	                        break;
	                }
	 
	                $request->setParams((array) $params);
	            }
	            catch (Exception $e)
	            {
	                $this->view->message = $e->getMessage();;
	                $this->getResponse()->setHttpResponseCode(400);
	     
	                $request->setControllerName('error');
	                $request->setActionName('error');
	                $request->setParam('error', $error);
	 
	                $request->setDispatched(true);
	 
	                return;
	            }
	        }
	    }
	}
	
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
		$this->getResponse()->setHeader('Vary', 'Accept');

		$mimeType = $this->getMimeType($request->getHeader('Accept'));

		switch ($mimeType) {
			case 'text/xml':
			case 'application/xml':
				$request->setParam('format', 'xml');
				break;

			case 'application/octet-stream':
				$request->setParam('format', 'amf');
				break;

			case 'text/php':
				$request->setParam('format', 'php');
				break;

			case 'application/json':
			default:
				$request->setParam('format', 'json');
				break;
		}
	}

	private function getMimeType($mimeTypes = null)
	{
		// Values will be stored in this array
		$AcceptTypes = Array ();

		// Accept header is case insensitive, and whitespace isn't important
		$accept = strtolower(str_replace(' ', '', $mimeTypes));

		// divide it into parts in the place of a ","
		$accept = explode(',', $accept);

		foreach ($accept as $a)
		{
			// the default quality is 1.
			$q = 1;

			// check if there is a different quality
			if (strpos($a, ';q='))
			{
				// divide "mime/type;q=X" into two parts: "mime/type" i "X"
				list($a, $q) = explode(';q=', $a);
			}

			// mime-type $a is accepted with the quality $q
			// WARNING: $q == 0 means, that mime-type isn't supported!
			$AcceptTypes[$a] = $q;
		}

		arsort($AcceptTypes);

		// let's check our supported types:
		foreach ($AcceptTypes as $mime => $q)
		{
			if ($q && in_array($mime, $this->availableMimeTypes))
			{
				return $mime;
			}
		}
		// no mime-type found
		return null;
	}
}