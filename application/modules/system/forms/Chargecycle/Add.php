<?php

class System_Form_Chargecycle_Add extends System_Form_Chargecycle_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('charge_cycle_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

