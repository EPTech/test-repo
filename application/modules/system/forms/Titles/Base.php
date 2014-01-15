<?php

class System_Form_Titles_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {
       
        //Charge entity
        $this->addElement('text', 'title_name', array(
            'label' => 'Title',
            'required' => true,
          ));
        
         //charge id
        $this->addElement('hidden', 'title_id');
 
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

