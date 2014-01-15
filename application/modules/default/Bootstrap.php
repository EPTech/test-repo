<?php

class Default_Bootstrap extends Zend_Application_Module_Bootstrap {

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

   
}

