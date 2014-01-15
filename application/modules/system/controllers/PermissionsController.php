<?php
class System_PermissionsController extends Zend_Controller_Action
{
	protected $_model;
	
    public function init()
    {
    	if($this->getRequest()->isXmlHttpRequest()){
	    	$this->_helper->layout()->disableLayout();
    	}
    	
    	$this->_model = new System_Model_DbTable_Permissions();
    	
    }

    public function indexAction()
    {
    	$id = $this->_getParam('role');
    	$this->view->roleid = $id;
    	if(!is_null($id)){
    		$roleModel = new System_Resource_Adminprofile();
    		$found = $roleModel->find($id);
    		
    		if($found->count()){
    			$this->view->role = $found->current();
    			
	    		$select = $this->_model->select();
	    		$select->where('role_id = ?', $id);
	    		
	    		$permissions = $this->_model->fetchAll($select);
	    		
	    		$resourcesModel = new System_Model_DbTable_Resources();
	    		$resources = $resourcesModel->fetchAll();
	    		$this->view->resources = $resources;
	    		
	    		$access = array();
	    		
	    		foreach ($resources as $resource) {
	    			$allow = false;
	    			
		    		foreach ($permissions as $permission) {
		    			if($permission->resource_id == $resource->id){
		    				$allow = true;
		    				break;
		    			}
		    		}
		    		
		    		$access[$resource->id] = $allow;
		    		
	    		}
	    	
	    		$this->view->access = $access;
    		
    		}else{
    			$this->_helper->flashMessenger('Cannot find role. Please select a role from the list below to view its permissions.');
    			$this->_helper->redirector('index');    			
    		}
    	}else{
    	
    		$this->_forward('select', 'roles');
    		return;
    	}    	
    	
    }

    public function grantAction()
    {
    	$request = $this->getRequest();
    	$role = $request->getUserParam('role');
    	if($request->isPost()){
    		foreach($_POST['resourceid'] as $resource){
    			
    			$this->_model->deny($_POST['role'], $resource);
    			$this->_model->grant($_POST['role'], $resource);
    		}
    		
    		$role = $_POST['role'];
    		$this->_helper->flashMessenger(array('success'=>'Permission granted for selected resource(s).'));
    	}
    	else{
    	$resource = $request->getUserParam('resource');
    	
    	$this->_model->grant($role, $resource);
    	
    	$this->_helper->flashMessenger(array('success'=>'Permission granted.'));
    	}
    	//exit;
    	$this->_helper->redirector('index', null, null, array('role'=>$role));
    }

    public function denyAction()
    {
    	$request = $this->getRequest();
    	$role = $request->getUserParam('role');
    	$resource = $request->getUserParam('resource');
    	
    if($request->isPost()){
    		foreach($_POST['resourceid'] as $resource){
    			
    			$this->_model->deny($_POST['role'], $resource);
    			//$this->_model->grant($_POST['role'], $resource);
    		}
    		$role = $_POST['role'];
    		$this->_helper->flashMessenger(array('success'=>'Permission denied for selected resource(s).'));
    	}
    	else {
    	$this->_model->deny($role, $resource);
    	
    	$this->_helper->flashMessenger(array('success'=>'Permission denied.'));
    	}
    	$this->_helper->redirector('index', null, null, array('role'=>$role));
    }
    
    public function processEntryAction(){
        $id = $this->_getParam('id');
    	$userFrm = new System_Form_User($id);
        
    	$request = $this->getRequest();
    	
    	if($request->isPost()){
    		if($userFrm->isValid($request->getPost())){
    			if($userFrm->submit($userFrm->getValues())){
    				$this->_helper->flashMessenger('User Submited');
    				$this->_redirect('/system/users');
    			}
    		}else{
    			$this->_helper->flashMessenger('User Not Submited');
    			$this->view->form = $userFrm;
    			return $this->render('entry');
    		}
    	}
    				
    	$this->_redirect('/system/users');
    }

    public function deleteAction(){
        $id = $this->_getParam('id');
        $ans = $this->_getParam('ans');
        
        if(!is_null($ans)){
            if($ans==1){
                try{
                    $table = new Zend_Db_Table('users');
                    $adapter = Zend_Db_Table::getDefaultAdapter();
                    $where = $adapter->quoteInto('id = ?', $id);
                    $table->delete($where);
                }catch(Exception $exc){
                    echo $exc->getTraceAsString();
                }
            }
            
            $this->_helper->redirector('index');
        }else{
            $this->view->referer = $this->getRequest()->getServer('HTTP_REFERER'); 
            $this->view->id = $id;  
        }
    }

    public function passwordAction(){
    	$form = $this->_getPasswordForm();
    	
    	//send form to view
    	$this->view->form = $form;
    }
    
    public function processPasswordAction(){
    	$form = $this->_getPasswordForm();
    	
    	$params = $this->_getAllParams();
    	
    	if($form->isValid($params)){
    		$data = $form->getValues();
    		
    		//get current office
    		$users = new System_Model_DbTable_Users();
    		$user_id = $this->_getParam('id');
    		$users->resetOfficePassword($user_id, $data['password']);
    		
    		$this->_helper->flashMessenger('Passowrd changed successfully');
    		
    		$this->_helper->redirector('view', 'users', null,array('id'=>$user_id));
    	}else{
    		$this->view->form = $form;
    		$this->render('password',null,null,array('id'=>$user_id));
    	}
    }
    
    protected function _getPasswordForm(){
    	//get office logged into
    	$office_id = $this->_getParam('id');
    	
    	//prepare form
    	$form = new System_Form_Password($office_id);
    	$form->setAction($this->view->url(array('action'=>'process-password')));
    	
    	return $form;
    }
    
    public function viewAction()
    {
        $id = $this->_getParam('id');
        $users = new System_Model_DbTable_Users();
    	$found = $users->find($id);
    	
    	if($found->count()){
    		$user = $found->current();
    	}
    	
    	$this->view->user = $user;
    }

    public function trailsAction()
    {
        $id = $this->_getParam('id');
        $trails = new System_Model_DbTable_Trails();
        $select = $trails->select()->order('login_ts DESC');
        
        if($id){
        	$select->where('user_id = ?', $id);
        }
        
    	$this->view->trails = $trails->fetchAll($select);
    }
}

