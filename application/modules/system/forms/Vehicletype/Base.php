<?php

class System_Form_Vehicletype_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {
       
        //Charge entity
        $this->addElement('text', 'vehicle_type', array(
            'label' => 'Vehicle type',
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

