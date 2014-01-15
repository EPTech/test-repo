<?php
abstract class My_Form_Wizard_Abstract2 extends Zend_Form
{	
    protected $_action;
    
    protected $_session;
    
    protected $_request;
    
    protected $_response;
    
    protected $_buttons;
    
    protected $_steps = array();
    
    protected $_step = null;
    
    protected $_form;
    
    public function __construct($step=null, $options=null){
    	$this->getView();
    	$front = Zend_Controller_Front::getInstance();
    	
    	$this->_request = $front->getRequest();
    	$this->_response = $front->getResponse();
    	$this->_session = new Zend_Session_Namespace(get_class($this));
    	
    	if(!$this->_session->referer){
    		$this->_session->referer = $this->_request->getServer('HTTP_REFERER');
    	}
    	//$this->cancel(); die;
    	
    	parent::__construct($options);
    	
    	$this->_step = $this->_request->getParam('step') ? $this->_request->getParam('step') : array_shift($this->getSteps());
    	
    	$this->_form = $this->{$this->_step};
    	
    	if(isset($this->_session->data))
    		$this->populate($this->_session->data);
    
		if(!$this->prevFormIsValid()){
	    	return $this->_response->setRedirect($this->_view->url(array('step'=>$this->getPrevStep($this->_step))));
		}
		
    }
    
    protected function _handleRequest(){
    	$data = $this->_request->getParam($this->_step);
		$pressed = $this->_request->getParam('wizardAction');
        
		if($pressed === 'Back'){
        	$prev = $this->getPrevStep($this->_step);
	    	return $this->_response->setRedirect($this->_view->url(array('step'=>$prev)));
		}
		
		if($pressed === 'Cancel'){
    	 	$referer = $this->_session->referer;
    		$this->cancel();
	    	return $this->_response->setRedirect($referer);   		
		}
            
        if($pressed === 'Finish'){
	    	return $this->_response->setRedirect($this->_action);
        }
		        
        if($this->_request->isPost()){
	        if ($this->_form->isValid($this->_request->getPost())) {
	        	$next = $this->getNextStep($this->_step);//die($next);
	        	$redirect = $this->_view->url(array('step'=>$next));//die($redirect);
		    	return $this->_response->setRedirect($redirect);
	        }

	        return;
        }
        
		
        //continue until form is valid
        if ($pressed === 'Next' && (!$this->isValid($this->getData()) || (end($this->getSteps()) != $this->_step))) { 
        	$next = $this->getNextStep($this->_step);//die($next);
        	$redirect = $this->_view->url(array('step'=>$next));//die($redirect);
	    	return $this->_response->setRedirect($redirect);
        } 
    }
    
    public function start(){
    	$this->_handleRequest();
    }
    
    public function getValues(){
    	return $this->getData();
    }
    
    public function setAction($action){
    	if($this->_step == end($this->_steps)){
        	parent::setAction($action);
    	}
    	
    	reset($this->_steps);
    	
        return $this;
    }
    
    protected function nextIsLast(){
    	$flag = false;
    	
    	reset($this->_steps);
    	
    	do{
    		if(current($this->_steps) == $this->_step){
    			
    			if(!next($this->_steps)){//next is last
    				$flag = true;
    			}
    				
    			break;
    			
    		}
    	}while(next($this->_steps));
    	
    	reset($this->_steps);
    	
    	return $flag;
    }
    
    public function addStep($name, My_Form_Wizard_Step $form, $legend=null, $description=null){
    	$form->setContext($this->_session);
    	$this->addSubForm($form, $name);
    	$form->setLegend($legend);
    	$form->setDescription($description);
    	
    	array_push($this->_steps, $name);
    	
    	return $this;
    }
    
    public function isStep($step){
    	$steps = $this->getSteps();
    	if(in_array($step, $steps)){
    		return true;
    	}
    	
    	return false;
    }

    /**
     * Get the session namespace we're using
     *
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace()
    { 
        return $this->_session;
    }
    
    public function cancel(){
    	$this->_session->unsetAll();
    	return $this;
    }
    
    public function getSteps(){
    	return $this->_steps;
    }
    
    public function getNextStep($current=null){
    	$steps = $this->getSteps();
    	
    	if(is_null($current)){
			$current = $this->_step;
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
			$current = $this->_step;
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
    	if($step == end($this->_steps)) return true;

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
        return $this->_form;
    }
    
    public function populateForm($form, $data){
		$form->populate($data);
    }
    
    public function render(Zend_View_Interface $view = null){
    	$this->_form->loadDefaultDecorators();
		
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
    	$step = $this->_step;
    	
    	if($this->isFirst($step)) return true;
    	
    	$prevForm = $this->getPrevForm($step);
    	
    	if(!$prevForm) return true;
    	
    	$prev = $this->getPrevStep($step);
    	
    	$data = $this->getData();
    	
    	if(!isset($data[$prev])) return false;
    	
    	if($prevForm->isValid($data)) return true;
    	
    	return false;
    }
    
    public function getData(){
    	$data = $this->_session->data;
    	//Zend_Debug::dump($this->_session->data); die;
    	return $this->_session->data ? $this->_session->data : array();
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
        
        if(current($steps) == $this->_step){
        	$this->disableBackButton();
        }
        
        if(end($steps) == $this->_step){
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