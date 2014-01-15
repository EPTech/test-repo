<?php

class System_Form_Role_Add extends System_Form_Role_Base {

    //put your code here
    public function init() {
        parent::init();
        //$this->removeElement('role_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

