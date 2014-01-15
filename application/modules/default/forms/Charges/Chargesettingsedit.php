<?php

class Default_Form_Charges_Chargesettingsedit extends Zend_Form_SubForm {

    //put your code here
    public function init() {

        $this->setDisableLoadDefaultDecorators('true');

        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'charges/_chargesettingsedit.phtml')),
            
        ));
     
        // $this->setMethod('post');
        //$this->setAction("");
    }

    public function addChargesetting($index) {
      //  echo "i am ".$index.'<br/>';
       
        $chargesettingForm = new Default_Form_Charges_Chargesetting($index);
        $this->addSubForm($chargesettingForm, $index);
        //$this->addSubForm($chargesettingForm, $index);
         $chargesettingForm->setElementsBelongTo("settings[$index]");
        
        return $chargesettingForm;
    }

}
?>

