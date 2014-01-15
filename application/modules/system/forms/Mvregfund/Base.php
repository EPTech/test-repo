<?php

class Default_Form_Mvregfund_Base extends My_Bootstrap_Form {

    //put your code here
    public function init() {

         // add path to custom validators
        $this->addElementPrefixPath(
            'Default_Validate',
            APPLICATION_PATH . '/modules/default/models/validate/',
            'validate'
        );
        
        $this->setAttrib('id','mvcardfundForm');
        
        //super card no
        $this->addElement('text', 'scardno', array(
            'label' => 'Supercard no. <a id="scardbalance" title="Supercard balance" class="ajax" href="/default/profiles/scardbalance">Check balance</a>',
            'required' => true,
            'escape' => false,
             'validators' => array(
                array('Db_RecordExists', true, array('table' => 'scardregister', 'field' => 'scardno', 'messages' => 'Supercard no. does not exist. Please enter a valid supercard no.'))
            ),
        ));

        //mvregno
        $this->addElement('text', 'mvregno', array(
            'label' => 'Mvreg no.',
            'required' => true ,
            'validators' => array(
              /*  array('UniqueEmail', false, array()), */
                array('Db_RecordExists', true, array('table' => 'mvregister', 'field' => 'mvregno', 'messages' => 'Mvreg no. does not exist. Please enter a valid mvregno no.'))
            ),
        ));

        //fund amount
        $this->addElement('text', 'fund_amount', array(
            'label' => 'Amount to fund',
            'validators' => array(
            array('Sufficientscardbalance', false, array(new Default_Resource_Scardregister() ))
                ),
            'required' => true,
             
                /* 'validators' => array(
                  array('Db_NoRecordExists', true, array('table' => 'rooms', 'field' => 'room_no', 'message' => 'Room no alread exist. Please enter another room no'))
                  ) */
        ));


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

