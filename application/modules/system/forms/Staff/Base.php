<?php

class System_Form_Staff_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');

        $roleTable = new Zend_Db_Table('roles');
        $roleSelect = $roleTable->select();
        $roles = $roleTable->fetchAll($roleSelect);
        $roleList = array();
        $roleList[""] = "Select";
        foreach ($roles as $role) {
            $roleList[$role->role_id] = $role->role_name;
        }

        $stateResource = new Default_Resource_States();
        $stateList = array();
        $stateList[""] = "Select";
        foreach ($stateResource->getStates() as $state) {
            $stateList[$state->state_id] = $state->state;
        }

        $lgaList = array();
        $lgaList[""] = "Select";

        //Username
        $this->addElement('text', 'staff_username', array(
            'label' => 'Staff Username',
            'required' => true,
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                  array('EmailAddress'),
                array('Db_NoRecordExists', true, array('table' => 'staff', 'field' => 'staff_username', 'messages' => 'username  already exist. Please enter another username'))
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
            'label' => 'Staff Password'
        ));

        $this->addElement('password', 'passwordVerify', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                'PasswordVerification',
            ),
            'required' => true,
            'label' => 'Confirm Password'
        ));

        //admin menu
        $this->addElement('select', 'role_id', array(
            'label' => 'Role',
            'required' => true,
            'multiOptions' => $roleList,
        ));

        //firstname           
        $this->addElement('text', 'surname', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Last Name',
        ));
        
             //firstname           
        $this->addElement('text', 'firstname', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'First Name',
        ));

        //firstname           
        $this->addElement('text', 'mobile', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'Phone no.',
        ));


        
        $this->addElement('text', 'dob', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'label' => 'Date of birth'
        ));

        $this->addElement('select', 'sex', array(
            'label' => 'Gender',
            'required' => true,
            'multiOptions' => array("" => "Select", "m" => "Male", "f" => "Female")
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
        $this->addElement('hidden', 'staff_id');

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

