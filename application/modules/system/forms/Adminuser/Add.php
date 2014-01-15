<?php

class System_Form_Adminuser_Add extends System_Form_Adminuser_Base {

    public function init() {
        //call parent
       
        
        //specialize form
       // $this->getElement('password')->setRequired(true);
       // $this->getElement('passwordVerify')->setRequired(t);
       
         
         parent::init();
         
        $this->getElement('submit')->setLabel('Save user');
        $this->removeElement('user_id');
    }

}

?>