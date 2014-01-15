<?php

class My_View_Helper_ModuleMenu extends Zend_View_Helper_Abstract{

    public function moduleMenu()
    {
    	$front = Zend_Controller_Front::getInstance();
    	$module = $front->getRequest()->getModuleName();
    	$container = $this->view->navigation()->findByLabel(ucfirst($module));
						
        return $this->view->navigation()->menu()->renderMenu($container);
    }
	
}
