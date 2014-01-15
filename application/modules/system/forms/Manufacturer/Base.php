<?php

class System_Form_Manufacturer_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {
       
        //Charge entity
        $this->addElement('text', 'manufacturer', array(
            'label' => 'Manufacturer',
            'required' => true,
          ));
        
         //charge id
        $this->addElement('hidden', 'id');
 
        //save btn
        $this->addElement('submit', 'sub', array(
            'class' => 'fan-sub btn btn-primary ',
            'required' => false,
            'ignore' => true,
            'label' => 'Submit',
            'decorators' => array("ViewHelper"
                )));
    }

}
?>

