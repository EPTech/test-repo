<?php

class System_Bootstrap extends Zend_Application_Module_Bootstrap {

    /**
     *
     * @return Zend_View
     */
    protected function _initAutoload() {
        $autoloader = $this->getResourceLoader();

        $autoloader->addResourceTypes(array(
            'modelResource' => array(
                'path' => 'models/resources',
                'namespace' => 'Resource',
            ),
            'service' => array(
                'path' => 'services',
                'namespace' => 'Service',
            )
        ));
    }

     protected function _initAccessControl() {
        $acl = System_Model_Acl::getInstance();
        //die;
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new My_Controller_Plugin_Access($acl));
    }
}

