<?php
      
class System_Form_Vehicleuse_Add extends System_Form_Vehicleuse_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('vehicle_use_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

