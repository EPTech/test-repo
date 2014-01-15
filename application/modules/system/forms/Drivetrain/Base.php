<?php

class System_Form_Drivetrain_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        //Charge entity
        $this->addElement('text', 'drive_train', array(
            'label' => 'Drive train',
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

