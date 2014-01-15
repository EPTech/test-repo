<?php

class System_Form_User_Add extends System_Form_User_Base {

    public function init() {
        //call parent
       
        
        //specialize form
       // $this->getElement('password')->setRequired(true);
       // $this->getElement('passwordVerify')->setRequired(t);
       
         
         parent::init();
         
        $this->getElement('submit')->setLabel('Add User');
        $this->removeElement('id');
    }

}

?>