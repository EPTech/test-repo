<?php

class System_Form_Vehiclecondition_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {
       
        //Charge entity
        $this->addElement('text', 'condition_type', array(
            'label' => 'Vehicle condition',
            'required' => true,
          ));
        
         //charge id
        $this->addElement('hidden', 'condition_id');
 
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

