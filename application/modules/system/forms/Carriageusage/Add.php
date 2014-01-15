<?php

class System_Form_Carriageusage_Add extends System_Form_Carriageusage_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

