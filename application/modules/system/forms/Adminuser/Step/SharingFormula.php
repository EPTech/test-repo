<?php

class System_Form_User_Step_SharingFormula extends My_Form_Wizard_Step {

    public function init() {
        $this->setLegend('Sharing Formula');
        $this->setDescription('Provide percentage share for each stakeholder.');

        $this->addElement('text', 'rems', array(
            'label' => 'REMS percentage',
            'required' => true,
        ))->addElement('text', 'govt', array(
            'label' => 'Percent for Government (Imo State)',
            'required' => true,
        ))->addElement('text', 'lead_banks', array(
            'label' => 'Percentage for Lead Banks',
            'required' => true,
        ))->addElement('text', 'collection_banks', array(
            'label' => 'Percentage for Collection Banks',
            'required' => true,
        ))->addElement('text', 'agents', array(
            'label' => 'Percentage for Agents',
            'required' => true,
        ));

        
        $this->setElementDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'alert alert-error')),
            'HtmlTag',
            'Label'
        ));
    }

}