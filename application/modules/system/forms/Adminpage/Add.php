<?php

class System_Form_Adminpage_Add extends System_Form_Adminpage_Base {

    public function init() {
        //call parent
        parent::init();
        $this->getElement('submit')->setLabel('Add');
       
    }

}

?>