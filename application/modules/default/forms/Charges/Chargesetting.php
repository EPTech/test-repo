<?php

class Default_Form_Charges_Chargesetting extends Zend_Form_SubForm {

    //put your code here
    protected $_index;

    public function __construct($index, $options = null) {
        $this->_index = $index;
        parent::__construct($options);
    }

    public function init() {

        $this->setDisableLoadDefaultDecorators('true');
       // $this->_setElementsBelongTo("paymentElement");
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'charges/_chargesetting.phtml')),
        ));

        $engcatResource = new Default_Resource_Enginecat();
        $engcat = $engcatResource->getEnginecatById($this->_index);

        $this->addElement('hidden', 'engcat_id', array(
            'value' => $engcat->engcat_id,
            'class' => 'rowindex',
            'decorators' => array(
                'ViewHelper'
            )
        ));

        $this->addElement('checkbox', 'charge_item', array(
            'decorators' => array(
                'ViewHelper',
                'Errors'
            ),
            'class' => 'checkbox',
        ));

        $this->addElement('hidden', 'engcat_title', array(
            'required' => true,
            'value' => $engcat->engcat_title,
            'decorators' => array(
                'ViewHelper',
                'Errors'
            )
        ));

        $this->addElement('text', 'charge_amount', array(
            'decorators' => array(
                'ViewHelper',
                'Errors'
            )
        ));

        //parent::init();
    }

}
?>

