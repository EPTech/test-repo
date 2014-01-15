<?php

class System_Form_Mlo_Edit extends System_Form_Mlo_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->getElement('password')->setRequired(false);
        $this->getElement('passwordVerify')->setRequired(false);
        $this->getElement('username')->removeValidator('Db_NoRecordExists');
         $this->getElement('email')->removeValidator('Db_NoRecordExists');
        //$this->getElement('sub')->setLabel('Update');
        //$this->getElement('room_no')->removeValidator('Db_NoRecordExists');
    }

}
?>

