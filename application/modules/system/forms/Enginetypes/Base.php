<?php

class System_Form_Enginetypes_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        //Charge entity
        $this->addElement('text', 'engcat_title', array(
            'label' => 'Engine category',
            'required' => true,
          ));
        
        
    
         //charge id
        $this->addElement('hidden', 'engcat_id');
 
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

