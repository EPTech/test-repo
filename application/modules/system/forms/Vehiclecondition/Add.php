<?php
      
class System_Form_Vehiclecondition_Add extends System_Form_Vehiclecondition_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('condition_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

