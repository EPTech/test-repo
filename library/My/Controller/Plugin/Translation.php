<?php

require_once 'Zend/Controller/Plugin/Abstract.php';

class My_Controller_Plugin_Translation extends Zend_Controller_Plugin_Abstract {
	
	private $_view;
    private $_translate;

    public function __construct(Zend_View_Abstract $view) {
        $this->_view = $view;
        
        $this->_translate = new Zend_Translate(
	    array(
	        'adapter' => 'csv',
	        'content' =>  APPLICATION_PATH . '/docs/languages/en.csv',
	        'locale'  => 'en',
	    	'delimiter' => ','
	    ));
        
        $this->_translate->addTranslation(
	    array(
	        'content' => APPLICATION_PATH . '/docs/languages/fr.csv',
	        'locale'  => 'fr'
	    ));
        
        $this->_translate->addTranslation(
	    array(
	        'content' => APPLICATION_PATH . '/docs/languages/ar.csv',
	        'locale'  => 'ar'
	    ));
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
 
    	$lang = $this->getLanguage();
    	
    	$this->_translate->setLocale($lang);
    	
    	$this->_view->setEncoding($this->getEncoding($lang));
    	$this->_view->dir = $this->getDir($lang);
    	$this->_view->translate = $this->_translate;
    	
        Zend_Registry::set('TranslationObject', $this->_translate);
    }
    
    public function getEncoding($locale){
    	switch($locale){
    		default:
    			return 'UTF-8';
    		case 'fr_FR':
    			return 'ISO-8859-1';
    	}
    }
    
    public function getDir($locale){
    	switch($locale){
    		default:
    			return 'ltr';
    		case 'ar_SA':
    			return 'rtl';
    	}
    }
    
    public function getLanguage(){
    	$locale = new Zend_Locale();
    	
    	$translationSession = new Zend_Session_Namespace('Translation');
    	//$translationSession->unsetAll();
    	if(isset($translationSession->lang)){
    		$requestedLang = $translationSession->lang;
    		$locale->setLocale($requestedLang);
    	}else{
//     		$locale->setLocale(Zend_Locale::BROWSER);
//     		$requestedLang = key($locale->getBrowser());
    	}
    	 
    	//Zend_Debug::dump($this->_translate->getList()); die;
    	if(in_array($requestedLang, $this->_translate->getList())){
    		$lang = $requestedLang;
    	}else{
    		$lang = 'en';
    	}
    	
    	return $lang;
    }

}