<?php

class System_Form_Adminprofile_Base extends Zend_Form {

    public function init() {

        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');


        //profile name
        $this->addElement('text', 'role_name', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'required' => true,
            'label' => 'Role Name',
            'validators' => array(
                array('UniqueRole', false, array(new System_Model_Adminuser())),
            ),
        ));




        $this->setElementDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'alert alert-error')),
            'HtmlTag',
            'Label'
        ));

          $element = new Zend_Form_Element_Radio('role_enabled', array('escape' => false)); //This is the key!
        $element->setLabel('Role status');
        $element->setRequired(true);
        $element->setSeparator('');   
        $element->setMultiOptions(array(
            1 => 'Active',
            0 => 'Blocked',
        )); //I pass the results from the database, already parsed for HTML here, in the "setMultiOptions" method of this element.  
       // $element->setSeparator('&nbsp;&nbsp;&nbsp;&nbsp;'); //setting the separator to null, disables Zend built-in separators for radio elements. 

        $element->addDecorator('Label', array('tag' => 'dt', 'class' => 'thisLabel')); //These pre/suffixes add the selected strings to the proper place on the label.
            $element->addDecorator('HtmlTag', array('tag' => 'dd', 'class' => 'finishChoices'));
        $element->addDecorator('Errors', array('class' => 'alert alert-error'));
        $element->setErrorMessages(array("Choose a gender "));


        $this->addElement($element);
        
        
        //submit button
        $this->addElement('submit', 'submit', array(
            'class' => 'fan-sub btn',
            'required' => false,
            'ignore' => true,
            'label' => 'Submit',
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'id' => 'form-submit')))
        ));

        $this->addElement('hidden', 'role_id', array(
            'filters' => array('StringTrim'),
            'required' => true,
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
    }

}

?>