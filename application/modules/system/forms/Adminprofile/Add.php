<?php

class System_Form_Adminprofile_Add extends System_Form_Adminprofile_Base {

    public function init() {
        //call parent
        parent::init();
        $this->getElement('submit')->setLabel('Add Profile');
       
    }

}

?>