<?php

class Default_Form_Charges_Add extends Default_Form_Charges_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('charge_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

