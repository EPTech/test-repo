<?php

class System_Form_Adminprofile_Edit extends System_Form_Adminprofile_Base {

    public function init() {
        //call parent
        parent::init();
        //specialize form
        $this->getElement('role_name')->setRequired(false);
         $this->getElement('role_name')->removeValidator('UniqueRole');
        $this->getElement('submit')->setLabel('Save profile');
    }

}

?>