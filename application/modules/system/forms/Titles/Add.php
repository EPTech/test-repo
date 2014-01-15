<?php

class System_Form_Titles_Add extends System_Form_Titles_Base {

    //put your code here
    public function init() {
        parent::init();
        $this->removeElement('title_id');
        $this->getElement('sub')->setLabel('Save');
    }

}
?>

