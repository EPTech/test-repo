<?php
abstract class My_Controller_Action_Wizard extends Zend_Controller_Action
{
	protected $_isXHR;
	
	protected $_step;
	
    public function __construct(Zend_Controller_Request_Abstract $request,
                                Zend_Controller_Response_Abstract $response,
                                array $invokeArgs = array()) {
    	
    	parent::__construct($request, $response, $invokeArgs);
    	
		$this->_step = $this->_getParam('step');
		
    }
	
	public function wizardAction(){	
			$wizard = $this->getWizard($this->_step);
			
			if(!$wizard->prevFormIsValid()){
		    	return $this->_redirect($this->view->url(array('step'=>$wizard->getPrevStep($this->_step))));
			}
			
	        $wizard->setAction($this->view->url(array('action'=>'process', 'step'=>$wizard->getCurrentStep())));
	        
	        $this->view->wizard = $wizard;
        
	}
 
    public function processAction()
    {
		$wizard = $this->getWizard($this->_step);
		
		$request = $this->getRequest();
		$data = $request->getParam($wizard->getCurrentStep());
		$action = $this->_getParam('wizardAction');
        
		if($action === 'Back'){
        	$prev = $wizard->getPrevStep($wizard->getCurrentStep());
	    	return $this->_redirect($this->view->url(array('action'=>'wizard', 'step'=>$prev)));
		}
		
		if($action === 'Cancel'){
    		$wizard->cancel();
	    	$this->_helper->flashMessenger('Wizard cancelled!');
	    	return $this->_helper->redirector('index');    		
		}
		
		$form = $wizard->getForm();
			
        if (!$form) {
	    	return $this->_redirect($this->view->url(array('action'=>'wizard'))); 
        }
        
        if (!$this->subFormIsValid($form, $this->getRequest()->getPost())) {
	        $this->view->wizard = $wizard;
	        return $this->render('wizard');
        }
        
		if($action === 'Finish'){  
			$this->finish();
			$wizard->cancel();
			return;
		}
		
        //continue until form is valid
        if (!$this->formIsValid() || (end($wizard->getSteps()) != $wizard->getCurrentStep())) { 
        	$next = $wizard->getNextStep($wizard->getCurrentStep());//die($next);
        	$redirect = $this->view->url(array('action'=>'wizard', 'step'=>$next));//die($redirect);
	    	return $this->_redirect($redirect);
        }
    }

	/**
     * Is the sub form valid?
     *
     * @param  Zend_Form_SubForm $subForm
     * @param  array $data
     * @return bool
     */
    public function subFormIsValid(My_Form_Wizard_Step $subForm,
                                   array $data)
    {
        return $subForm->isValid($data);
    }
 
    /**
     * Is the full form valid?
     *
     * @return bool
     */
    public function formIsValid()
    {
    	$wizard = $this->getWizard();
        $data = $wizard->getSessionNamespace()->data;
        //Zend_Debug::dump($data); die;
        
        return $wizard->isValid($data);
    }
    
    public function cancelAction(){
    	$this->getWizard()->cancel();
    }
    
    /**
     * 
     * The Wizard
     * @param string $step
     * @return My_Form_Wizard
     */
    public abstract function getWizard($step=null);
    
    public abstract function finish();
}