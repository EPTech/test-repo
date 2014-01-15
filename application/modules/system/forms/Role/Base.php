<?php

class System_Form_Role_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        $stateResource = new Default_Resource_States();
        $stateList = array();
        $stateList[""] = "Select";
        foreach ($stateResource->getStates() as $state) {
            $stateList[$state->state_id] = $state->state;
        }

        $lgaList = array();
        $lgaList[""] = "Select";

        //Role name
        $this->addElement('text', 'role_name', array(
            'label' => 'Role',
            'required' => true,
            'validators' => array(
                array('Db_NoRecordExists', true, array('table' => 'roles', 'field' => 'role_name', 'messages' => 'Role Already exist'))
            )
        ));

        //Role status
        $this->addElement('select', 'role_enabled', array(
            'label' => 'Role status',
            'required' => true,
            'multiOptions' => array("" => "Select", 1 => "Active", 0 => "Disabled")
        ));


        $roleResource = new System_Resource_Roles();
        $role = $roleResource->getLastRole();
        $roleId = 0;

        if ($role !== null) {
            $roleId = $role->role_id + 1;
        } else {
            $roleId = "001";
        }
       
        //role id
        $this->addElement('hidden', 'role_id', array(
            'value' => $roleId
        ));

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

