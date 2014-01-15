<?php

class System_Form_Adminuser_Edit extends System_Form_Adminuser_Base {

    public function init() {
        parent::init();
        // var_dump($this->getElement('username')->getValidator('UniqueUsername'));
        $this->getElement('username')->setRequired(false);
        $this->getElement('user_password')->setRequired(false);
        $this->getElement('user_email')->setRequired(false);
        $this->getElement('user_email')->removeValidator('UniqueEmail');
        $this->getElement('user_passwordVerify')->setRequired(false);
        $this->getElement('submit')->setLabel('Save user');
    }

}

?>