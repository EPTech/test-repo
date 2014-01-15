<?php

class System_Form_User_Edit extends System_Form_User_Base {

    public function init() {
        //call parent


       $this->addElement('select', 'status', array(
            'label' => 'Account Status',
             'required' => true,
            'multiOptions' => array('' => 'Select', 'active' => 'active', 'inactive' => 'inactive', 'deactivated' => 'deactivated'),
        ));


        parent::init();


        //specialize form
       $this->getElement('password')->setRequired(false);
        $this->getElement('passwordVerify')->setRequired(false);
        $this->getElement('submit')->setLabel('Save User');
    }

}

?>