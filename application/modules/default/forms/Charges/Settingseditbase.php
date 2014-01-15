<?php

class Default_Form_Charges_Settingseditbase extends My_Bootstrap_Form {

    //put your code here
    protected  $_settings;
    
    public function init() {
        $this->setDisableLoadDefaultDecorators('true');

        $this->setAttrib('id', 'chargeSettingForm');
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'charges/_settingseditbase.phtml')),
            'Form'
        ));
        
         $this->_settings = new Default_Form_Charges_Chargesettingsedit();
         $this->addSubForm($this->_settings, 'settings');
         
         $chargeResource = new Default_Resource_Charges();
        $chargeList = array();
        $chargeList[""] = "Select";
        foreach ($chargeResource->getCharges() as $charge) {
          
            $chargeList[$charge->charge_id] = $charge->charge_title;
        }
       
        
        $this->setMethod('post');
        
        //$this->setAction('/default/charges/reconfigure/');

      
        $this->addElement('hidden', 'charge_id',array(
            'label' => 'Charge',
           'required' => true,
            
            'decorators' => array(
                'Errors',
                'ViewHelper'
            )
        ));
        
         $this->addElement('hidden', 'charge_name',array(
            'label' => 'Charge name',
           'required' => true,
            
            'decorators' => array(
                'Errors',
                'ViewHelper'
            )
        ));
         
          $this->addElement('text', 'flatfee',array(
              'value' => '0.0',
              'required' => true,
             'decorators' => array(
                'Errors',
                'ViewHelper'
            )
        ));
         
        
        
         $this->addElement('submit', 'sub', array(
            'label' => 'Save',
            'class' => 'btn btn-primary'
        ));
    }

     public function setDefaults(array $values) {
        foreach ($values['settings'] as $key => $value) {
            $chargesettingForm = new Default_Form_Charges_Chargesetting();
            $this->_settings->addSubForm($chargesettingForm, $key);
        }

        parent::setDefaults($values);
    }
    
    }
?>

