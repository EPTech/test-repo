<?php

class System_Form_Mlo_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');


        $stateResource = new Default_Resource_States();
        $stateList = array();
        $stateList[""] = "Select";
        foreach ($stateResource->getStates() as $state) {
            $stateList[$state->state_id] = $state->state;
        }

        $lgaList = array();
        $lgaList[""] = "Select";

        //Username
        $this->addElement('text', 'mlo_name', array(
            'label' => 'MLO Name',
            'required' => true,
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('Db_NoRecordExists', true, array('table' => 'mlo', 'field' => 'mlo_name', 'messages' => 'mlo name  already exist. Please enter another name'))
            )
        ));

        //Username
        $this->addElement('text', 'username', array(
            'label' => 'MLO Username',
            'required' => true,
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('Db_NoRecordExists', true, array('table' => 'admin_users', 'field' => 'username', 'messages' => 'username  already exist. Please enter another username'))
            )
        ));

        /* 'validators' => array(
          array('Db_NoRecordExists', true, array('table' => 'rooms', 'field' => 'room_no', 'message' => 'Room no alread exist. Please enter another room no'))
          ) */

        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'required' => true,
            'label' => 'MLO Password'
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                'PasswordVerification',
            ),
            'required' => true,
            'label' => 'Confirm MLO Password'
        ));





        //firstname           
        $this->addElement('text', 'phone', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Phone no.',
        ));


        $this->addElement('text', 'email', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
                array('Db_NoRecordExists', true, array('table' => 'admin_users', 'field' => 'email', 'messages' => 'The Email already exist. Please enter another email'))
            ),
            'label' => 'Email'
        ));



        //firstname           
        $this->addElement('textarea', 'address', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'rows' => 3,
            'cols' => 4,
            'label' => 'Address',
        ));

        //state
        $this->addElement('select', 'state', array(
            'label' => 'State',
            'required' => true,
            'multiOptions' => $stateList
        ));

        //lga
        $this->addElement('select', 'lga', array(
            'label' => 'Lga',
            'required' => true,
            'multiOptions' => $lgaList,
            'registerInArrayValidator' => false
        ));

        //charge id
        $this->addElement('hidden', 'mlo_id');

        //save btn
        $this->addElement('submit', 'sub', array(
            'class' => 'fan-sub btn btn-primary ',
            'required' => false,
            'ignore' => true,
            'label' => 'Submit',
            'decorators' => array("ViewHelper"
                )));
    }

}
?>

