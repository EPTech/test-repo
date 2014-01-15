<?php

class Default_Form_Charges_Stock extends My_Bootstrap_Form {

    //put your code here
    public function init() {
      
       //stock quantity
        $this->addElement('text', 'qty', array(
            'label' => 'Quantity',
            'required' => true,
        ));
     
         //stock id
       $this->addElement('hidden', 'charge_id');
 
        //save btn
        $this->addElement('submit', 'sub', array(
            'class' => 'fan-sub btn btn-primary ',
            'required' => false,
            'ignore' => true,
            'label' => 'Add stock',
            'decorators' => array("ViewHelper"
                )));
    }

}
?>

