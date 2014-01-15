<?php

require_once ('Zend/Form.php');

class My_Bootstrap_Form_SubForm extends My_Bootstrap_Form {
    /**
     * Whether or not form elements are members of an array
     * @var bool
     */
    protected $_isArray = true;

    /**
     * Load the default decorators
     *
     * @return Zend_Form_SubForm
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Description')
            	->addDecorator('FormElements')
                 //->addDecorator('HtmlTag', array('tag' => 'dl'))
                 ->addDecorator('Fieldset');
        }
        return $this;
    }
}