<?php
      
class System_Form_Mla_Add extends System_Form_Mla_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('mla_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

