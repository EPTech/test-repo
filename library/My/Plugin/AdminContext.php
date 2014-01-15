<?php

/**
 * SF_Plugin_AdminContext
 * 
 * This plugin detects if we are in the admininstration area
 * and changes the layout to the admin template.
 * 
 * This relies on the admin route found in the initialization plugin
 *
 * 
 */
class My_Plugin_AdminContext extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();

        //begin end
        $action_name = $request->getActionName();
        $moduleExceptions = array('explorer', 'imgbnk');
        $controllerExceptions = array('files', 'images');
        $actionExceptions = array('thumb', 'dump', 'change-pass', 'renewpassword');
       //and (!in_array($action_name, $actionExceptions)) and (!in_array($controllerName, $controllerExceptions)) and (!in_array($moduleName, $moduleExceptions)
        if (!Zend_Auth::getInstance()->hasIdentity() ) {

           
            if ($request->isPost()) {
                //echo '2'; exit;
                $request->setModuleName('system');
                $request->setControllerName('user');
                $request->setActionName('authenticate');
            } else {
                //  echo '3'; exit;
                $request->setModuleName('system');
                $request->setControllerName('user');
                $request->setActionName('login');
            }
            // Set the module if you need to as well.
        }else if(!My_Auth::getInstance()->hasIdentity()){
            
            if ($request->isPost()) {
                //echo '2'; exit;
                $request->setModuleName('system');
                $request->setControllerName('user');
                $request->setActionName('authenticatestaff');
            } else {
                //  echo '3'; exit;
                $request->setModuleName('system');
                $request->setControllerName('user');
                $request->setActionName('loginstaff');
            }
           // echo "yes"; die;
        }
        //end
    }

}

