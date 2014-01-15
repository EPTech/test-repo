<?php

class System_Form_Enginetypes_Add extends System_Form_Enginetypes_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('engcat_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

