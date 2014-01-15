<?php

class System_Form_User_Passwordchange extends Zend_Form {

    public function init() {

        //add path to cusom validators
        $this->addElementPrefixPath("System_Validate", APPLICATION_PATH . '/modules/system/models/validate/', 'validate');

        //    var_dump($this->getDecorator('Errors')); exit;
        $this->addElement('text', 'username', array(
            'class' => 'input-text',
            'id' => 'email',
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('UserExist', false, array(new System_Model_Adminuser()))
            ),
            'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array('id' => 'email-label'))),
            'required' => true,
            'label' => 'Username',
        ));

        $this->addElement('password', 'user_oldpassword', array(
            'class' => 'input-text',
            'id' => 'oldpassword',
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128)),
                 array('PasswordExist', false, array(new System_Model_Adminuser()))
            ),
            'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array('id' => 'password-label'))),
            'required' => true,
            'label' => 'Old Password',
        ));

        $this->addElement('password', 'user_password', array(
            'class' => 'input-text',
            'id' => 'oldpassword',
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
            'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array('id' => 'password-label'))),
            'required' => true,
            'label' => 'New Password',
        ));

        $this->addElement('password', 'user_passwordVerify', array(
            'class' => 'input-text',
            'id' => 'oldpassword',
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128)),
                   'PasswordVerification',
            ),
           
            'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array('id' => 'password-label'))),
            'required' => true,
            'label' => 'Confirm New Password',
        ));

        $this->setElementDecorators(array(
            'ViewHelper',
            array('Errors', array('class' => 'alert alert-error')),
            'HtmlTag',
            'Label'
        ));

        $this->addElement('submit', 'login', array(
            'class' => 'nice radius button yellow',
            'required' => false,
            'ignore' => true,
            'label' => 'Confirm',
            'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay')))
        ));
    }

}

?>