<?php

class System_Form_Vehicleuse_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {
       
        //Charge entity
        $this->addElement('text', 'vehicle_use', array(
            'label' => 'Vehicle use',
            'required' => true,
          ));
        
         //charge id
        $this->addElement('hidden', 'vehicle_use_id');
 
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

