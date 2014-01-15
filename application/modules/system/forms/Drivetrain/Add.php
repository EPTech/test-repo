<?php

class System_Form_Drivetrain_Add extends System_Form_Drivetrain_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

