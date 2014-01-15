<?php
class My_Form_Element_RandGenerator extends Zend_Form_Element_Hidden{
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        parent::__construct($spec, $options);
    }
    
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('RandGenerator')
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array(
                     'tag'   => 'p',
                     'class' => 'description'
                 ))
                 ->addDecorator('HtmlTag', array(
                     'tag' => 'dd',
                     'id'  => $this->getName() . '-element'
                 ))
                 ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
}