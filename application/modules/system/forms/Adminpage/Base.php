<?php

class System_Form_Adminpage_Base extends Zend_Form {

    public function init() {
        
        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');
        
        
        //prefill admin menu from database
        $admin_menu = new System_Model_Adminpage();
        $menus = $admin_menu->getAdminMenus();
        $menuopt = array();
        foreach($menus as  $val){
            $menuopt[$val->id] = $val->menu_key;
        }
        
        //admin menu
          $this->addElement('select', 'admin_menu_id', array(
            'label' => 'Admin Menu',
             'required' => true,
            'multiOptions' => $menuopt,
        ));

          
         //label
        $this->addElement('text', 'label', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'required' => true,
            'label' => 'Label'
        ));
        
        
         //module
        $this->addElement('text', 'module', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'required' => true,
            'label' => 'Module'
        ));
        
        //controller
        $this->addElement('text', 'controller', array(
            'filters' => array('StringTrim', 'StringToLower'),
           
            'required' => true,
            'label' => 'Controller'
        ));

        
        //action
        $this->addElement('text', 'action', array(
            'filters' => array('StringTrim', 'StringToLower'),
          
            'required' => true,
            'label' => 'Action'
        ));
        
        
          //description
        $this->addElement('textarea', 'description', array(
            'style' => "height:50px;",
            'filters' => array('StringTrim', 'StringToLower'),
           
            'required' => true,
            'label' => 'Description'
        ));
        
         // hidden elements
          $this->addElement('hidden', 'time_created', array(
              'filters' => array('StringTrim'),
            
              'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
          
          
          // date created
             $this->addElement('hidden', 'date_created', array(
              'filters' => array('StringTrim'),
           
              'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
           
         //admin user id    
           $this->addElement('hidden', 'admin_user_id', array(
              'filters' => array('StringTrim'),
            
              'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay')))
        ));    
          
           $this->setElementDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'alert alert-error')),
            'HtmlTag',
            'Label'
        ));
           
        //submit button
        $this->addElement('submit', 'submit', array(
            'class' => 'fan-sub btn',
            'required' => false,
            'ignore' => true,
            'label' => 'Submit',
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'id' => 'form-submit')))
        ));
        
        
    }

}

?>