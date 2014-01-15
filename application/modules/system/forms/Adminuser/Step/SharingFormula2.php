<?php

class System_Form_User_Step_SharingFormula2 extends My_Form_Wizard_Step {

    public function init() {
        $this->setLegend('Sharing Formula');
        $this->setDescription('Provide percentage share for each stakeholder.');

        $this->addElement('text', 'rems2', array(
            'label' => 'REMS percentage',
            'required' => true,
        ))->addElement('text', 'govt2', array(
            'label' => 'Percent for Government (Imo State)',
            'required' => true,
        ))->addElement('text', 'lead_banks2', array(
            'label' => 'Percentage for Lead Banks',
            'required' => true,
        ))->addElement('text', 'collection_banks2', array(
            'label' => 'Percentage for Collection Banks',
            'required' => true,
        ))->addElement('text', 'agents2', array(
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