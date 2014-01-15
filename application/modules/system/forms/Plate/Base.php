<?php

class System_Form_Plate_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {
        
 $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');

        //add path to cusom validators
     $this->setAttrib("class","form-horizontal");
        $stateResource = new Default_Resource_States();
        $stateList = array();
        $stateList[""] = "Select";
        foreach ($stateResource->getStates() as $state) {
            $stateList[$state->state_id] = $state->state;
        }

        $lgaList = array();
        $lgaList[""] = "Select";


        $this->addElement('checkbox', 'multiple_plates', array(
            'label' => 'Do you want to create multiple plates',
        ));


        $this->addElement('checkbox', 'multiple_plates', array(
            'label' => 'Do you want to create multiple plates',
        ));
        //MLA lastname
        $this->addElement('checkbox', 'plates_serial', array(
            'label' => 'Are the plates serial',
            'required' => true,
        ));

        $this->addElement('text', 'first_plateno', array(
            'label' => 'First Platenumber (e.g RSH-788AP)',
            'required' => true,
            'validators' => array(
                array('PlatePattern', false, array()),
            ),
        ));

        $this->addElement('text', 'no_plates', array(
            'label' => 'Number of plates to generate',
            'required' => true,
        ));


        $this->addElement('text', 'plateno', array(
            'label' => 'Platenumber (e.g RSH-788AP)',
            'required' => false,
            'validators' => array(
                array('PlatePattern', false, array()),
            ),
        ));

        $pcategory = new Zend_Db_Table("plate_categories");
        $pcatSelect = $pcategory->select();
        $pcategories = $pcategory->fetchAll($pcatSelect);
        $pcatList = array();
        $pcatList[""] = "Select";
        foreach ($pcategories as $pcat) {
            $pcatList[$pcat->cat_id] = $pcat->category;
        }

        $this->addElement('select', 'pcategory', array(
            'label' => 'Plate category',
            'required' => true,
            'multiOptions' => $pcatList
        ));


        //state
        $this->addElement('select', 'state', array(
            'label' => 'State',
            'required' => true,
            'multiOptions' => $stateList
        ));

        //lga
        $lgaList = array();
        $lgaList[""] = "Select";
        $this->addElement('select', 'lga', array(
            'label' => 'Lga',
            'required' => true,
            'multiOptions' => $lgaList,
            'registerInArrayValidator' => false
        ));

        //charge id
        $this->addElement('hidden', 'pid');

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

