<?php
      
class System_Form_Staff_Add extends System_Form_Staff_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('staff_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

