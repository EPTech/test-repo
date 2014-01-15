<?php

class System_ResourcesController extends Zend_Controller_Action
{
    public function init()
    {
    	if($this->getRequest()->isXmlHttpRequest()){
	    	$this->_helper->layout()->disableLayout();
    	}
      	  $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    /**
     * View list of system resources
     */
    public function indexAction()
    {
       
    	$resources = new System_Model_DbTable_Resources();
    	//$roles = new System_Model_DbTable_Roles();
    	
    	$this->view->resources = $resources->fetchAll();
    	//$this->view->roles = $roles->fetchAll();
    }

    /**
     * Build resource list
     */
    public function buildAction()
    {
    	System_Model_AccessControl::build();
    //	$this->_helper->flashMessenger('Resource List rebuilt successfully!');
    	$this->_helper->redirector('index');
    }
    
    /**
     * View resource permissions
     */
    public function permissionsAction(){
    	//get resource id
    	$id = $this->_getParam('id');
    	
    	if(!is_null($id)){
    		$this->view->resource = $id;
    		
    		$resourcesTable = new System_Model_DbTable_Resources();
    		$found = $resourcesTable->find($id);
    		
    		if($found->count()){
    			$permissionsTable = new System_Model_DbTable_Permissions();
    			$this->view->role = $found->current();
    			
	    		$select = $permissionsTable->select();
	    		$select->where('resource_id = ?', $id);
	    		
	    		$permittedRoles = $permissionsTable->fetchAll($select);
	    		
	    		$rolesTable = new System_Resource_Adminprofile();
	    		$roles = $rolesTable->fetchAll();
	    		$this->view->roles = $roles;
	    		
	    		$access = array();
	    		
	    		foreach ($roles as $role) {
	    			$allow = false;
	    			
		    		foreach ($permittedRoles as $permittedRole) {
		    			if($permittedRole->role_id == $role->role_id){
		    				$allow = true;
		    				break;
		    			}
		    		}
		    		
		    		$access[$role->role_id] = $allow;
		    		
	    		}
	    		
	    		//var_dump($access); die();
	    		
	    		$this->view->access = $access;
    		
    		}else{
    			//$this->_helper->flashMessenger('Cannot find resource.');
    			$this->_helper->redirector('index');    			
    		}
    	}   
    		
    }
    
    /**
     * Grant permission to a resource
     */
    public function grantAction()
    {
    	$permissionsTable = new System_Model_DbTable_Permissions();
    	
    	$request = $this->getRequest();
    	$role = $request->getUserParam('role');
    	$resource = $request->getUserParam('id');
    if($request->isPost()){
    	$resource = $_POST['resourceid'];
    		foreach($_POST['roleid'] as $role){
    			
    			$permissionsTable->deny($role, $resource);
    			$permissionsTable->grant($role, $resource);
    			
    		}
    		//$this->_helper->flashMessenger(array('success'=>'Permission granted for selected role(s).'));
    	}
    	else{
    	$permissionsTable->grant($role, $resource);
    	
    	//$this->_helper->flashMessenger(array('success'=>'Permission granted.'));
    	}
    	$this->_helper->redirector('permissions', null, null, array('id'=>$resource));
    }

    /**
     * Deny permission to resource
     */
    public function denyAction()
    {
    	$permissionsTable = new System_Model_DbTable_Permissions();
    	
    	$request = $this->getRequest();
    	$role = $request->getUserParam('role');
    if($request->isPost()){
    	$resource = $_POST['resourceid'];
    		foreach($_POST['roleid'] as $role){
    			
    			$permissionsTable->deny($role, $resource);
    			//$permissionsTable->grant($role, $resource);
    			
    		}
    		//$this->_helper->flashMessenger(array('success'=>'Permission denied for selected role(s).'));
    	}
    	else{
    	$resource = $request->getUserParam('id');
    	
    	$permissionsTable->deny($role, $resource);
    	
    	//$this->_helper->flashMessenger(array('success'=>'Permission denied.'));
    	}
    	$this->_helper->redirector('permissions', null, null, array('id'=>$resource));
    }
    
}

