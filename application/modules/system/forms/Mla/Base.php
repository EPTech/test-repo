<?php

class System_Form_Mla_Base extends My_Bootstrap_Form {

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

        $this->addElement('select','role_id', array(
           'label' => 'Role',
            'multiOptions' => $roleList,
            'required' => true
        ));
        
         //MLA lastname
        $this->addElement('text', 'mla_lastname', array(
            'label' => 'Lastname',
            'required' => true,
           
        ));
        
          //MLA firstname
        $this->addElement('text', 'mla_firstname', array(
            'label' => 'Firstname',
            'required' => true,
           
        ));
        
          //MLA mobile phone
        $this->addElement('text', 'mla_mobile_phone', array(
            'label' => 'Phone',
            'required' => true,
           
        ));
        
          //MLA office phone
        $this->addElement('text', 'mla_office_phone', array(
            'label' => 'Office Phone',
        ));
        
         $this->addElement('select', 'gender', array(
            'label' => 'Gender',
            'required' => true,
             'multiOptions' => array('' => "Select", "m" => "Male", "f" => "Female")
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
        $this->addElement('hidden', 'mla_id');

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

