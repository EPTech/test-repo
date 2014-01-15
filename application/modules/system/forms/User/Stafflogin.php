<?php

class System_Form_User_Stafflogin extends My_Bootstrap_Form
{
    public function init()
    {   
          $this->setDecorators(array(
            array('Description', array( 'class' => 'alert alert-error')),
            array('ViewScript', array('viewScript' => 'user/_loginstaff.phtml')),
            'Form'
        ));
     
            $this->getDecorator('Description')->setEscape(false);
    //    var_dump($this->getDecorator('Errors')); exit;
        $this->addElement('text', 'user_email', array(
            'class' => 'span12',
            'placeholder' => "Username",
            'label' => 'Staff username',
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress'),
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
           'label' => 'Staff password',
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
            "height" => "30px;",
            'ignore'   => true,
            'label'    => 'Login',
            /*  'decorators' => array("ViewHelper", array('HtmlTag',
                    array('tag' => 'dd', 'class' => 'noDisplay'))) */
        )); 
      
    }
}


?>