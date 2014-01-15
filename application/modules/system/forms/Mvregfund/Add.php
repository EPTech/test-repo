<?php

class Default_Form_Mvregfund_Add extends Default_Form_Mvregfund_Base {

    //put your code here
    public function init() {
        parent::init();
        //$this->removeElement('room_id');
        $this->getElement('sub')->setLabel('Fund');
    }

}
?>

