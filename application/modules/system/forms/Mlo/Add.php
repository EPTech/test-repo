<?php

class System_Form_Mlo_Add extends System_Form_Mlo_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('mlo_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

