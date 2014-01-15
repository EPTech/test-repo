<?php

class System_Form_User_Base extends Zend_Form {

    public function init() {
          $this->setDecorators(array(
            'Form',
            array('Description', array('class' => 'alert alert-error')),
          
            ));
        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');
        
        //prefill admin menu from database
        $admin_model = new System_Model_Adminpage();
        $adminprofiles = $admin_model->getaclAdminprofiles();
        $profileopt = array();
        foreach($adminprofiles as  $adminprofile){
            $profileopt[$adminprofile->role_id] = $adminprofile->role_name;
        }
        
        //admin menu
          $this->addElement('select', 'role_id', array(
            'label' => 'Role',
             'required' => true,
            'multiOptions' =>  $profileopt,
        ));
          
        //firstname           
        $this->addElement('text', 'fullname', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'FullName',
        ));

         
        $this->addElement('text', 'email', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
                array('UniqueEmail', false, array(new System_Model_User())),
            ),
            'required' => true,
            'label' => 'Email'
        ));
        

        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
          'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required' => true,
            'label' => 'Password'
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters' => array('StringTrim'),
            'validators' => array(
               'PasswordVerification',
            ),
            'required' => true,
            'label' => 'Confirm Password'
        ));
        
          $this->setElementDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'alert alert-error')),
            'HtmlTag',
            'Label'
        ));

        $this->addElement('submit', 'submit', array(
            'class' => 'fan-sub btn',
            'required' => false,
            'ignore' => true,
            'label' => 'Submit',
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'id' => 'form-submit')))
        ));
        
      
        
          $this->addElement('hidden', 'id', array(
              'filters' => array('StringTrim'),
              'required' => true,
              'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
          
           
    }

}

?>