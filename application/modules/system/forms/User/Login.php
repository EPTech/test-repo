<?php

class System_Form_User_Login extends My_Bootstrap_Form
{
    public function init()
    {   
          $this->setDisableLoadDefaultDecorators('true');

        $this->setDecorators(array(
            array('Description', array( 'class' => 'alert alert-error')),
            array('ViewScript', array('viewScript' => 'user/_login.phtml')),
            'Form'
        ));
//          $this->setDecorators(array(
//          
//            array('Description', array( 'class' => 'alert alert-error')),
//               "FormElements",
//               'Form'
//            ));
     
            $this->getDecorator('Description')->setEscape(false);
    //    var_dump($this->getDecorator('Errors')); exit;
        $this->addElement('text', 'user_email', array(
            'class' => 'span12',
            'placeholder' => "Username",
            'id' => 'email',
            'label' => 'Mlo username',
            'required' => true,
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128))
            ),
            /* 'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array( 'id' =>'email-label'))),
            'required'   => true,
            'label'      => 'Email', */
        ));
        
        $this->addElement('password', 'user_password', array(
            'class' => 'span12',
            'placeholder' => "Password",
            'id' => 'password',
            'label' => 'Mlo password',
            'required' => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(6, 128))
            ),
       /*     'decorators' => array(
                'ViewHelper',
                array('Errors', array('class' => 'error')),
                array('Label', array( 'id' =>'password-label'))),
            'required'   => true,
            'label'      => 'Password', */
        ));

//          $this->setElementDecorators(array(
//            'ViewHelper',
//            array('Errors', array('class' => 'alert alert-error')),
//            'Label'
//        ));
          
        $this->addElement('submit', 'login', array(
            'class' => 'width-35 pull-right  btn btn-small btn-primary',
            'required' => false,
            'ignore'   => true,
            'label'    => 'Login',
            'style' => "height : 30px; ",
            'decorators' => array(
                "ViewHelper"
            )
            /*  'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay'))) */
        )); 
      
    }
}


?>