<?php

class System_Form_Mla_Change extends My_Bootstrap_Form {

    //put your code here
    public function init() {

        //add path to cusom validators
      

         //Username
        $this->addElement('select', 'mla_id', array(
            'label' => 'Select Mla',
            'required' => true,
            'multiOptions' => array("" => "Select")
            /*
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('Db_NoRecordExists', true, array('table' => 'admin_users', 'field' => 'username', 'messages' => 'username  already exist. Please enter another username'))
            )
             * 
             */
        ));
        
       $this->addElement('hidden', 'mlo_id');
        //save btn
        $this->addElement('submit', 'sub', array(
            'class' => 'fan-sub btn btn-primary ',
            'required' => false,
            'ignore' => true,
            'label' => 'Save',
            'decorators' => array("ViewHelper"
                )));
    }

}
?>

