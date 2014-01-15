<?php

class Default_Form_Charges_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        $ccycleResource = new Default_Resource_Chargecycles();
        $cycleList = array();
        $cycleList[""] = "Select";
        foreach ($ccycleResource->getChargecycles() as $cycle) {
            $cycleList[$cycle->charge_cycle_id] = $cycle->charge_cycle_title;
        } 
        
        //$this->cycleList = new Default_Model_ChargeList();
      
       
        //Charge entity
        $this->addElement('select', 'charge_entity', array(
            'label' => 'Charge Entity',
            'required' => true,
            'multiOptions' => array('' => "Select", "individual" => "Indivdual", 'vehicle' => "Vehicle", "corporate" => "Corporate")
        ));
        
        
        //Charge cycle
        $this->addElement('select', 'charge_cycle', array(
            'label' => 'Charge cycle',
            'required' => true,
            'multiOptions' => $cycleList
        ));

        //charge title
        $this->addElement('text', 'charge_title', array(
            'label' => 'Charge title',
            'required' => true
        ));

         //charge id
        $this->addElement('hidden', 'charge_id');
 
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

