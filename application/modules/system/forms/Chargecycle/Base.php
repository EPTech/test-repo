<?php

class System_Form_Chargecycle_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        //Charge cycle title
        $this->addElement('text', 'charge_cycle_title', array(
            'label' => 'Charge cycle',
            'required' => true,
        ));

        //Charge cycle addition string
        $this->addElement('text', 'charge_cycle_addition_string', array(
            'label' => 'php addition format',
            'required' => true,
        ));


        //charge id
        $this->addElement('hidden', 'charge_cycle_id');

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

