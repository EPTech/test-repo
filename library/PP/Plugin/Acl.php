<?php

/**
 * Application Acl Plugin
 * 
 * Handles access control
 * 
 */
class PP_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        /*
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            //change layout if user is not logged in
//            $layout = Zend_Layout::getMvcInstance();
//            $layout->setLayout('login');
            if ($request->isPost()) {
                $request->setControllerName('user');
                $request->setActionName('authenticate');
            } else {
                $request->setControllerName('user');
                $request->setActionName('login');
            }
            // Set the module if you need to as well.
        }
       */
    }

}