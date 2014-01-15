<?php
      
class System_Form_Plate_Add extends System_Form_Plate_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('pid');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

