<?php
class System_Form_Adminuser_Base extends Zend_Form {

    public function init() {
        $this->setDisableLoadDefaultDecorators(true);
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'admin/_admin.phtml')),
            'Form'
        ));
        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');

        //prefill admin role from database
        $admin_model = new System_Model_Adminpage();
        $adminprofiles = $admin_model->getaclAdminprofiles();
        $profileopt = array();
        foreach ($adminprofiles as $adminprofile) {
            $profileopt[$adminprofile->role_id] = $adminprofile->role_name;
        }

        //prefill countries from database
        $adminuserModel = new System_Model_Adminuser();
        $countries = $adminuserModel->getAllCountries();
        $countryArr = array();
        $countryArr[""] = 'Select';
        foreach ($countries as $country) {
            $countryArr[$country->countries_id] = $country->countries_name;
        }



        //admin menu
        $this->addElement('select', 'role_id', array(
            'label' => 'Role',
            'requiredPrefix' => '* ',
            'required' => true,
            'multiOptions' => $profileopt,
        ));

        //countries
        $this->addElement('select', 'countries_id', array(
            'label' => 'Country',
            'requiredPrefix' => '*',
            'required' => true,
            'multiOptions' => $countryArr,
        ));

        //firstname           
        $this->addElement('text', 'user_firstname', array(
            'filters' => array('StringTrim'),
            'requiredPrefix' => '* ',
            'required' => true,
            'label' => 'First Name',
        ));



        //lastname           
        $this->addElement('text', 'user_lastname', array(
            'filters' => array('StringTrim'),
            'requiredPrefix' => '* ',
            'required' => true,
            'label' => 'Last Name',
        ));

        //user mobile phone
        $this->addElement('text', 'user_mobile_phone', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Mobile phone number',
        ));

        //user office phone
        $this->addElement('text', 'user_office_phone', array(
            'filters' => array('StringTrim'),
            'label' => 'Office phone number',
        ));

        //user sms phone
        $this->addElement('text', 'user_sms_phone', array(
            'filters' => array('StringTrim'),
            'label' => 'Sms phone number',
        ));


        //user fax
        $this->addElement('text', 'user_fax', array(
            'filters' => array('StringTrim'),
            'label' => 'Fax',
        ));

        //location
        $this->addElement('text', 'user_location', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Office location',
        ));

        //address
        $this->addElement('text', 'user_address', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Address',
        ));

        //hint question
        $this->addElement('text', 'hint_question', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'description' => 'Enter a question to remind you of your Password',
            'label' => 'Password hint question'
        ));

        //password hint answer
        $this->addElement('text', 'hint_answer', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Password hint answer',
        ));


        //google instant message
        $this->addElement('text', 'user_im_google', array(
            'filters' => array('StringTrim'),
            'label' => 'Google IM',
            'description' => 'Enter your google email address'
        ));

        //yahoo instant message
         $this->addElement('text', 'user_im_yahoo', array(
            'filters' => array('StringTrim'),
            'label' => 'Yahoo IM',
            'description' => 'Enter your yahoo email address'
        ));
         
          $this->addElement('text', 'user_im_skype', array(
            'filters' => array('StringTrim'),
            'label' => 'Skype IM',
            'description' => 'Enter your skype email address or username'
        ));
         
          $this->addElement('text', 'user_im_payporte', array(
            'filters' => array('StringTrim'),
            'label' => 'Payporte IM',
            'description' => 'Enter your payporte email address or username'
        ));
         
          
        $this->addElement('text', 'user_email', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'requiredPrefix' => '* ',
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
                array('UniqueEmail', false, array(new System_Model_User())),
            ),
            'required' => true,
            'label' => 'Primary Contact email'
        ));

        $this->addElement('text', 'username', array(
            'requiredPrefix' => '*',
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('UniqueUsername', false, array(new System_Model_User())),
            ),
            'required' => true,
            'label' => 'Username'
        ));

        $this->addElement('password', 'user_password', array(
            'requiredPrefix' => '* ',
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required' => true,
            'label' => 'Password'
        ));

        $this->addElement('password', 'user_passwordVerify', array(
            'requiredPrefix' => '* ',
            'filters' => array('StringTrim'),
            'validators' => array(
                'PasswordVerification',
            ),
            'required' => true,
            'label' => 'Confirm Password'
        ));

        //
        $this->setElementDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'alert alert-error')),
            array('Description',array('class' => 'alert alert-info', 'style' => 'width:220px;')),
            'HtmlTag',
            'Label'
        ));

        $this->addElement('submit', 'submit', array(
            'class' => 'fan-sub btn',
            'required' => false,
            'ignore' => true,
            'label' => 'Save ',
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

        $this->addElement('checkbox', 'user_account_disable', array(
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
            'class' => 'group-ctrl',
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
                )
        );

        $this->addElement('checkbox', 'day_2', array(
            'label' => "Tuesday",
            'class' => 'group-ctrl',
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_3', array(
            'label' => "Wednesday",
            'class' => 'group-ctrl',
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_4', array(
            'label' => "Thursday",
            'class' => 'group-ctrl',
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));
        $this->addElement('checkbox', 'day_5', array(
            'label' => "Friday",
            'class' => 'group-ctrl',
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_6', array(
            'label' => "Saturday",
            'class' => 'group-ctrl',
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));

        $this->addElement('checkbox', 'day_7', array(
            'label' => "Sunday",
            'class' => 'group-ctrl',
            'decorators' => array(
                'ViewHelper',
                array('Label', array("style" => 'display:inline', 'placement' => 'append'))
            )
        ));
        //end days allowed to login
        //gender
//extend working hours
        //extend global working hours
        $this->addElement('text', 'extend_wh', array(
            'filters' => array('StringTrim'),
            "description" => "HH:mm-HH:mm",
            'label' => 'Enter working period',
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

        /*
          $gender = $this->addElement('radio', 'gender', array(
          'label' => 'Gender',
          'decorators' => array(
          array('ViewHelper', array('class' => 'benjay')),
          array('Label', array("style" => 'display:inline;', 'placement' => 'append')),
          ),
          'multiOptions' => array(
          'm' => 'Male',
          'f' => 'Female',
          ),
          ));
         * 
         */
        $element = new Zend_Form_Element_Radio('gender', array('escape' => false)); //This is the key!
        $element->setLabel('Gender');
        $element->setRequired();

        $element->setMultiOptions(array(
            'm' => 'Male',
            'f' => 'Female',
        )); //I pass the results from the database, already parsed for HTML here, in the "setMultiOptions" method of this element.  
        $element->setSeparator(''); //setting the separator to null, disables Zend built-in separators for radio elements. 

        $element->addDecorator('Label', array('tag' => 'dt', 'class' => 'thisLabel', 'requiredPrefix' => '* ', ' requiredSuffix' => ':', 'optionalSuffix' => ':')); //These pre/suffixes add the selected strings to the proper place on the label.
        //     $element->addDecorator('HtmlTag', array('tag' => 'dd', 'class' => 'finishChoices'));
        $element->addDecorator('Errors', array('class' => 'alert alert-error'));
        $element->setErrorMessages(array("Choose a gender "));

        $this->addElement($element);
    }

}

?>