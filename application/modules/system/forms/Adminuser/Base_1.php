<?php

class System_Form_Adminuser_Base extends Zend_Form {

    public function init() {
     //  $this->setDisableLoadDefaultDecorators(false);
//        $this->setDecorators(array(
//             'Errors',
//            array('ViewScript', array('viewScript' => 'admin/_admin.phtml')),
//            'Form',
//           
//        ));
        
      //  $this->addDecorators( array('ViewScript', array('viewScript' => 'admin/_admin.phtml')));
       // $this->addDecorator(array('ViewScript', array('viewScript' => 'admin/_admin.phtml')));
       
        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');

        //prefill admin menu from database
        $admin_model = new System_Model_Adminpage();
        $adminprofiles = $admin_model->getaclAdminprofiles();
        $profileopt = array();
        foreach ($adminprofiles as $adminprofile) {
            $profileopt[$adminprofile->profile_name] = $adminprofile->profile_name;
        }

        //admin menu
        $this->addElement('select', 'role_id', array(
            'label' => 'Role',
            'required' => true,
            'multiOptions' => $profileopt,
        ));

        //firstname           
        $this->addElement('text', 'user_firstname', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'First Name',
        ));



        //lastname           
        $this->addElement('text', 'user_lastname', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Last Name', 'decorators' => array(
                'ViewHelper',
                "Errors",
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        //user mobile phone
        $this->addElement('text', 'user_mobile_phone', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Mobile',
        ));





/*

        $this->addElement('text', 'user_email', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
                array('UniqueEmail', false, array(new System_Model_User())),
            ),
            'required' => true,
            'label' => 'Email'
        ));

        $this->addElement('text', 'username', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('UniqueUsername', false, array(new System_Model_User())),
            ),
            'required' => true,
            'label' => 'Username'
        ));

        $this->addElement('password', 'user_password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required' => true,
            'label' => 'Password'
        ));

        $this->addElement('password', 'user_passwordVerify', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                'PasswordVerification',
            ),
            'required' => true,
            'label' => 'Confirm Password'
        ));

*/
        //
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
            'label' => 'Save user',
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'id' => 'form-submit')))
        ));

        $this->addElement('checkbox', 'user_password_status', array(
            'label' => "Change Password at Next logon",
            'value' => 2,
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
                )
        );

        $this->addElement('checkbox', 'user_account_lock', array(
            'label' => "Check here to lock this user",
            'value' => 0,
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
                )
        );

        $this->addElement('checkbox', 'user_account_disabled', array(
            'label' => "Check here to disable this user",
            'value' => 0,
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
                )
        );
        //begin days allowed to login
        $this->addElement('checkbox', 'day_1', array(
            'label' => "Monday",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
                )
        );

        $this->addElement('checkbox', 'day_2', array(
            'label' => "Tuesday",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_3', array(
            'label' => "Wednesday",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_4', array(
            'label' => "Thursday",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));
        $this->addElement('checkbox', 'day_5', array(
            'label' => "Friday",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_6', array(
            'label' => "Saturday",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_7', array(
            'label' => "Sunday",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));
        //end days allowed to login
        //extend working hours
        //extend global working hours
        $this->addElement('text', 'extend_wh', array(
            'filters' => array('StringTrim'),
            'required' => true,
            "description" => "HH:mm-HH:mm",
            'label' => 'Extend global working hours',
            'decorators' => array(
                array('Label', array("style" => 'display:inline')),
                'ViewHelper',
                array('Description', array('style' => 'display:inline', 'class' => 'alert alert-info'))
            )
        ));

        $this->addElement('checkbox', 'override_wh', array(
            'label' => "Override Global working hours",
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));




        $this->addElement('hidden', 'user_id', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
        
         $this->setElementDecorators(array(
            'ViewHelper',
             'Errors',
            array('Errors', array('class' => 'alert alert-error')),
            'HtmlTag',
            'Label'
        ));
    }

    
}

?>