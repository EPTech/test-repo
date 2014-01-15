<?php

class System_Form_Manufacturer_Add extends System_Form_Manufacturer_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

