<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	/**
	 * @var Zend_Log
	 */
    
	protected $_logger;
	/**
	 * @var Zend_View
	 */
	protected $_view;

	/**
	 * @var Zend_Controller_Front
	 */
	public $frontController;

	protected function _initLogging() {
		$this->bootstrap('frontController');
		$logger = new Zend_Log();

		$writer = 'Production' == $this->getEnvironment() ?
				new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/logs/app.log') :
				new Zend_Log_Writer_Firebug();
		$logger->addWriter($writer);

		if ('production' == $this->getEnvironment()) {
			$filter = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
			$logger->addFilter($filter);
		}

		$this->_logger = $logger;
		Zend_Registry::set('log', $logger);
	}

	protected function _initDbProfiler() {
		$this->_logger->info('Bootstrap ' . __METHOD__);

		if ($this->getEnvironment() !== 'production') {
			$this->bootstrap('db');
			$profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
			$profiler->setEnabled(true);
			if ($this->hasPluginResource('db')) {
				$this->getPluginResource('db')->getDbAdapter()->setProfiler($profiler);
			}
		}
	}


	/**
	 * Setup the view
	 */
	protected function _initView() {
           // die("ojima");
		$this->_logger->info('Bootstrap ' . __METHOD__);

		$this->_view = Zend_Layout::startMvc()->getView();

		// set encoding and doctype
		// $this->_view->setEncoding('UTF-8');
		$this->_view->doctype('HTML5');

		// set the content type and language
		$this->_view->headMeta()->appendName('viewport', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
		$this->_view->headMeta()->appendName('apple-mobile-web-app-capable', 'yes');

		$this->_view->headTitle('Mvreg 2.0');

		// setting a separator string for segments:
		$this->_view->headTitle()->setSeparator(' - ');
		
		$this->_view->addHelperPath(APPLICATION_PATH . "/views/helpers", 'Zend_View_Helper_');
		
		$this->_view->headScript()->prependFile($this->_view->baseUrl('/vendors/jquery/js/jquery-1.7.2.min.js'));
			
	}
	
	
	public function _initActionHelpers() {
		Zend_Controller_Action_HelperBroker::addPath('My/Controller/Action/Helper', 'My_Controller_Action_Helper_');
	}

         protected function _initViewHelpers() {
        $view = $this->_getView();

        $view->addHelperPath('My/View/Helper/', 'My_View_Helper_');
    }
    
     protected function _getView() {
        if ($this->_view == null) {
            $this->bootstrap('view');
            $this->_view = $this->getResource('view');
        }

        return $this->_view;
    }
	
    protected function _initNavigation(){
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $config = new Zend_Config_Xml(APPLICATION_PATH."/configs/navigation.xml", "nav");
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
    }
}

?>