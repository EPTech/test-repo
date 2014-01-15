<?php

class System_Form_User_Register extends System_Form_User_Base {

    public function init() {
        //call parent
        parent::init();
        
        //specialize form
        $this->removeElement('id');
        $this->getElement('submit')->setLabel('Register User');
    }

}

?>