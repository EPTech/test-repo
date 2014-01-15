<?php

class System_Form_User_Bases extends Zend_Form {

    public function init() {
     //firstname           
        $this->addElement('text', 'fullname', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'label' => 'FullName',
        ));
        
        
        $this->addElement('submit', 'submit', array(
            'class' => 'fan-sub btn',
            'required' => false,
            'ignore' => true,
            'label' => 'Submit',
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'id' => 'form-submit')))
        ));
        
      

    }

    
}

?>