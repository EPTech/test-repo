<?php

class My_Form_Element_FingerPrintCapture extends Zend_Form_Element_Xhtml {

    private $_template_id = null;

    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);

        $this->addPrefixPath(
                'My_Form_Decorator', 'My/Form/Decorator', 'decorator'
        );
    }
    
    public function setTemplateID($value){
        $this->_template_id = $value;
        return $this;
    }
    
    public function getTemplateID(){
        return $this->_template_id;
    }

    public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FingerPrintCapture')
                    ->addDecorator('Errors')
                    ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                    ->addDecorator('HtmlTag', array(
                        'tag' => 'dd',
                        'id' => $this->getName() . '-element'
                    ))
                    ->addDecorator('Label', array('tag' => 'dt'));
        }
    }

}