<?php
abstract class My_Form_Wizard_Abstract extends Zend_Form
{	
    protected $_action;
    
    protected $_session;
    
    protected $_buttons;
    
    protected $_steps = array();
    
    protected $_step = null;
    
    protected $_form;
    
    public function __construct($step=null, $options=null){
    	parent::__construct($options);
    	
    	if(!is_null($step)){
    		$this->_step = $step;
    	}
    	
    	$this->setForm($this->_step);
    }
    
    public function addStep($name, $form, $legend=null, $description=null){
    	$this->addSubForm($form, $name);
    	
    	if(!is_null($legend))
    	$form->setLegend($legend);
    	
    	if(!is_null($description))
    	$form->setDescription($description);
    	
    	return $this;
    }
    
    public function setStep($step){
    	$this->_step = $step;
    	return $this;
    }
    
    public function isStep($step){
    	$steps = $this->getSteps();
    	if(in_array($step, $steps)){
    		return true;
    	}
    	
    	return false;
    }

    public function getCurrentStep(){
    	if(is_null($this->_step)){
    		$steps = $this->getSteps();
    		reset($steps);
			$this->_step = array_shift($steps);
    	}
    	
		return $this->_step;
    }

    /**
     * Get the session namespace we're using
     *
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace()
    {
        if (null === $this->_session) {
        	$class = get_class($this);
        	
            $this->_session =
                new Zend_Session_Namespace($class);
        }
 
        return $this->_session;
    }
    
    public function cancel(){
    	$this->getSessionNamespace()->unsetAll();
    	return $this;
    }
    
    public function getSteps(){
    	if(empty($this->_steps)){
    		$this->_steps = array_keys($this->getSubForms());
    	}
    	
    	return $this->_steps;
    }
    
    public function getNextStep($current=null){
    	$steps = $this->getSteps();
    	
    	if(is_null($current)){
			$current = $this->getCurrentStep();
    	}
    	
    	if(!$this->isStep($current) || $this->isLast($current)){
    		return false;
    	}
    	
    	$next = true;
    	
    	do{
    		if(current($steps) == $current){
    			$next = next($steps);
    			break;
    		}else{
    			next($steps);
    		}
    	}while($next);
    	
		return $next;
    }
    
    public function getPrevStep($current){
    	$steps = $this->getSteps();
    	
    	if(is_null($current)){
			$current = $this->getCurrentStep();
    	}
    	
    	if(!$this->isStep($current) || $this->isFirst($current)){
    		return false;
    	}
    	
    	$prev = true;
    	
    	do{
    		if(current($steps) == $current){
    			$prev = prev($steps);
    			break;
    		}else{
    			next($steps);
    		}
    	}while($prev);
    	
		return $prev;
    }

    public function isFirst($step){
    	$steps = $this->getSteps();
    	
    	if($step == array_shift($steps)) return true;

    	return false;
    }
    
    public function isLast($step){
    	$steps = $this->getSteps();
    	
    	if($step == end($steps)) return true;

    	return false;
    }
    

    /**
     * Get the current form to display
     *
     * @param  string|Zend_Form_SubForm $spec
     * @return Zend_Form_SubForm
     */
    public function getForm()
    {
    	$step = $this->getCurrentStep();
             
        $data = $this->getData();
        
		if(isset($data[$step])){
			$this->populateForm($this->_form, $data);
		}
		
        return $this->_form;
    }
    
    public function setForm($step=null){
    	if(is_null($step)){
    		$step = $this->getCurrentStep();
    	}
    	
        $this->_form = $this->{$step};
        
        $this->setLegend($this->_form->getLegend());
        $this->setDescription($this->_form->getDescription());
        $this->setErrors($this->_form->getErrors());
             
        $data = $this->getData();
        
		if(isset($data[$step])){
			$this->populateForm($this->_form, $data);
		}
    }
    
    public function populateForm($form, $data){
		$form->populate($data);
    }
    
    public function render(Zend_View_Interface $view = null){
    	$this->getForm()->loadDefaultDecorators();
		
		$this->addSubmitButton();
		
    	return parent::render($view);
    }
    
    /**
     * Prepare a sub form for display
     *
     * @param  string|Zend_Form_SubForm $spec
     * @return Zend_Form_SubForm
     */
    public function getPrevForm($step)
    {
    	if($this->isFirst($step)){
    		return false;
    	}
    	
    	$prev = $this->getPrevStep($step);
    	
        $subForm = $this->{$prev};
              
        $data = $this->getData();
        
		if(isset($data[$step])){
			$this->populateForm($subForm, $data);
		}
		
        return $subForm;
    }
    
    public function prevFormIsValid(){
    	$step = $this->getCurrentStep();
    	
    	if($this->isFirst($step)) return true;
    	
    	$prevForm = $this->getPrevForm($step);
    	
    	if(!$prevForm) return true;
    	
    	$prev = $this->getPrevStep($step);
    	
    	$data = $this->getData();
    	
    	if(!isset($data[$prev])) return false;
    	
    	if($prevForm->isValid($data)) return true;
    	
    	return false;
    }
    
    public function getData($suppressArrayNotation = false){
    	$data = $this->getSessionNamespace()->data;
    	
    	if($suppressArrayNotation){
    		$supressedData = array();
    		foreach ($data as $formData) {
    			$supressedData = array_merge($supressedData, $formData) ;
    		}
    		
    		return $supressedData;
    	}
    	
    	return $this->getSessionNamespace()->data;
    }
    
    /**
     * Add form decorators to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function setSubFormDecorators(Zend_Form_SubForm $subForm)
    {
        $subForm->loadDefaultDecorators();
        
        return $this;
    }
 
    /**
     * Add a submit button to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubmitButton()
    {
		
    	$this->_buttons = new My_Form_Wizard_Buttons('wizardAction', array(
			'required' => true,
		));
		
        $this->addElement($this->_buttons);
	
        
        $steps = $this->getSteps();
        
        if(current($steps) == $this->getCurrentStep()){
        	$this->disableBackButton();
        }
        
        if(end($steps) == $this->getCurrentStep()){
        	$this->disableNextButton();
        	$this->enableFinishButton();
        }
        
        return $this;
    }
    
    public function disableBackButton(){
    	$this->_buttons->setBackDisabled(true);
    }
    
    public function disableNextButton(){
    	$this->_buttons->setNextDisabled(true);
    }
    
    public function enableFinishButton(){
    	$this->_buttons->setFinishDisabled(false);
    }
}