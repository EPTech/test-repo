<?php

class System_Form_User_Login extends Zend_Form
{
    public function init()
    {               
        $this->addElement('text', 'email', array(
            'class' => 'input-text',
            'id' => 'email',
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
            ),
             'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array( 'id' =>'email-label'))),
            'required'   => true,
            'label'      => 'Email',
        ));
        
        $this->addElement('password', 'password', array(
            'class' => 'input-text',
            'id' => 'password',
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array( 'id' =>'password-label'))),
            'required'   => true,
            'label'      => 'Password',
        ));

        $this->addElement('submit', 'login', array(
            'class' => 'nice radius button yellow',
            'required' => false,
            'ignore'   => true,
            'label'    => 'Login',
        ));
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend', 'class' => 'error')),
            'Form'
        ));
    }
}


?>