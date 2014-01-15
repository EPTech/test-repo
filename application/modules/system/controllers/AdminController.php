<?php

class System_AdminController extends Zend_Controller_Action {

    protected $_model;
    protected $_adminModel;
    protected $_authService;

    public function init() {

        //get the default model
        $this->_authService = new System_Service_Authentication($this->_model);
        $this->_model = new System_Model_Adminpage();
        $this->_adminModel = new System_Model_Adminuser();

        //add forms
        $this->view->pageaddForm = $this->getPageaddForm();
        $this->view->profileaddForm = $this->getProfileaddForm();
        $this->view->profileEdit = $this->getProfile_editForm();
        //init flashmessenger
        $this->view->adduser = $this->getAdminuserAddForm();
        $this->view->edituser = $this->getAdminuserEditForm();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction() {
        
    }

    public function adduserAction() {
        
    }

    public function saveuserAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('index');
        }

        if (false === ($this->_adminModel->registerAdmin($request->getPost()))) {
            // var_dump($request->getPost());
            return $this->render('adduser');
        }
        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>User created sucessful</strong>"));
        $this->_helper->redirector('index');
    }

    public function editadminuserAction() {
        $userId = $this->_getParam('id', 1); //will be from session
        if (null === $this->_getParam('id')) {
            $this->_helper->redirector('index');
        }
        $user = $this->_adminModel->getUserById($userId);
        $this->view->edituser = $this->getAdminuserEditForm()->populate(
                $user->toArray()
        );
    }

    public function viewadminuserAction() {
        $userId = $this->_getParam('id', 1); //will be from session
        if (null === $this->_getParam('id')) {
            $this->_helper->redirector('index');
        }
        $this->view->user = $this->_adminModel->getUserDetailsById($userId);
//        $this->view->edituser = $this->getAdminuserEditForm()->populate(
//                $user->toArray()
//        );
        //$this->resetAdminuserEditForm($this->view->edituser);
    }

  
    public function updateuserAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('index');
        }

        if (false === ($this->_adminModel->saveadminUser($request->getPost()))) {
            return $this->render('editadminuser');
        }
        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>User details updated sucessfully</strong>"));
        $this->_helper->redirector('listadminuser');
    }

     public function ajaxassignuserAction() {
        $adminuser_select = $this->_adminModel->getUsers();
       
        $fields = array('username', 'role_name', 'user_lastname', 'user_firstname', 'user_email', 'user_mobile_phone', 'last_used', 'countries_name');
        $data = new My_DataTable($adminuser_select, $fields, $_GET);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $data->getTotal(),
            "iTotalDisplayRecords" => $data->getFilteredTotal(),
            "aaData" => array(),
        );

        foreach ($data->fetchAll() as $user) {
            $row = array();
            //  $row[] = '<img src="/css/images/details_open.png">';
             $row[] = '<input class="group-id" type="checkbox" name="userid[]" value="' . $user->user_id . '" />';
           
             $row[] = $user->user_lastname . ' ' . $user->user_firstname;
            $row[] = $user->role_name;
            $row[] = $user->countries_name;
             if ($user->user_account_disable == 0) {
                $enableStatus = '<span style=" display:inline-block; height: 18px; width: 16px; margin: 0 auto;  background-image: url(/css/images/activate.png); background-repeat: none;">&nbsp;</span>';
            } else if ($user->user_account_disable == 1) {
                $enableStatus = '<span style=" display:inline-block; height: 18px; width: 16px; margin: 0 auto;  background-image: url(/css/images/block.png); background-repeat: none;">&nbsp;</span>';
            }
          
            $address = "mailto:".$user->user_email;
            $row[] =  $enableStatus." <a style='color:#005580' href='$address'>".$user->user_email.'</a>';
            //  $row[] = $user->user_mobile_phone;
            $enableStatus = "";
           
            //  $acctStatus = ( $user->user_account_disable == 1 ) ?  '<span class="badge badge-success">Active</span>' :  '<span class="badge badge-important">Blocked</span>'; 
          
            $output['aaData'][] = $row;
        }

        $this->_helper->json($output);
    }

    
    public function ajaxcomponentAction() {
        $adminuser_select = $this->_adminModel->getUsers();
       
        $fields = array('username', 'role_name', 'user_lastname', 'user_firstname', 'user_email', 'user_mobile_phone', 'last_used', 'countries_name');
        $data = new My_DataTable($adminuser_select, $fields, $_GET);

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $data->getTotal(),
            "iTotalDisplayRecords" => $data->getFilteredTotal(),
            "aaData" => array(),
        );

        foreach ($data->fetchAll() as $user) {
            $row = array();
            $row[] = '<input class="group-id" type="checkbox" name="userid[]" value="' . $user->user_id . '" />';
            //  $row[] = '<img src="/css/images/details_open.png">';
            $row[] = $user->username;
            $row[] = $user->role_name;
            $row[] = $user->user_lastname . ' ' . $user->user_firstname;
            $address = "mailto:".$user->user_email;
            $row[] = "<a style='color:#005580' href='$address'>".$user->user_email.'</a>';
            //  $row[] = $user->user_mobile_phone;
            $enableStatus = "";
            if ($user->user_account_disable == 0) {
                $enableStatus = '<span style=" display:block; height: 18px; width: 16px; margin: 0 auto;  background-image: url(/css/images/activate.png); background-repeat: none;">&nbsp;</span>';
            } else if ($user->user_account_disable == 1) {
                $enableStatus = '<span style=" display:block; height: 18px; width: 16px; margin: 0 auto;  background-image: url(/css/images/block.png); background-repeat: none;">&nbsp;</span>';
            }
            $row[] = $enableStatus;
            //  $acctStatus = ( $user->user_account_disable == 1 ) ?  '<span class="badge badge-success">Active</span>' :  '<span class="badge badge-important">Blocked</span>'; 
            //  $row[] =  $acctStatus;
            $row[] = $user->last_used;
            $actionHtml = '<div class="btn-group">
                            <a href="/system/admin/viewadminuser/id/' . $user->user_id . '" class="btn btn-mini">'.$this->view->translate('Action').'</a>
                            <button data-toggle="dropdown" class="btn btn-mini dropdown-toggle"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="/system/admin/editadminuser/id/' . $user->user_id . '"><i class="icon icon-edit"></i>&nbsp;&nbsp;&nbsp;'.$this->view->translate('Edit').'</a></li>
                                <li class="divider"></li>
                                <li><a href="/system/admin/removeadminuser/id/' . $user->user_id . '"><i class="icon icon-remove-circle"></i>&nbsp;&nbsp;&nbsp;'.$this->view->translate('Delete').'</a></li>
                                <li class="divider"></li>
                                <li><a href="/system/admin/viewadminuser/id/' . $user->user_id . '"><i class="icon icon-eye-open"></i>&nbsp;&nbsp;&nbsp;All details</a></li>
                            </ul>
                           </div>';
            $row[] = $actionHtml;
            // $row[] = '<a href="' . $urlHelper ->url(array('module' => 'system', 'controller' => 'admin', 'action' => 'edit', 'id' => $user->user_id), null, true) . '"><i class="icon-edit"></i>&nbsp;Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;  '.' '.'<a href="' . $urlHelper ->url(array('module' => 'system', 'controller' => 'admin', 'action' => 'remove', 'id' => $user->user_id), null, true) . '"><i class="icon-edit"></i>&nbsp;Delete</a>';

            $output['aaData'][] = $row;
        }

        $this->_helper->json($output);
    }

    public function listadminuserAction() {
        
    }

    public function removeadminusersAction() {
        $request = $this->getRequest();

        if (!$request->isPost() and (null === $this->_getParam('id'))) {
            $this->_helper->redirector('index');
        }

        if (isset($_POST['userid'])) {
            $success = $this->_adminModel->deleteUsers($_POST['userid']);
        } else if ($this->_getParam('id')) {
            $success = $this->_adminModel->deleteUserById($this->_getParam('id'));
        }
        if ($success) {
            $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>user(s) deleted</strong>"));
        } else {
            $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>There was an error deleting user</strong>"));
        }
        $this->_helper->redirector('listadminuser');
    }

    public function addpageAction() {
        
    }

    public function addprofileAction() {
        
    }

    public function editprofileAction() {
        if ((null === $this->_getParam('id'))) {
            $this->_helper->redirector('index');
        }

        $profileId = $this->_getParam('id', 1); //will be from session
        $this->view->profile_name = $this->_model->getAdminprofileById($profileId);
        $this->view->profileEdit = $this->getProfile_editForm()->populate(
                $this->view->profile_name->toArray()
        );
    }

    public function saveprofileAction() {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $this->_helper->redirector('listprofile');
        }
        $date = new Zend_Date();
        $date_val = $date->get(Zend_Date::YEAR) . "-" . $date->get(Zend_Date::MONTH) . "-" . $date->get(Zend_Date::DAY);
        $time_val = $date->get(Zend_Date::HOUR_AM) . ":" . $date->get(Zend_Date::MINUTE_SHORT) . ":" . $date->get(Zend_Date::SECOND_SHORT);

        $default = array("created" => $date_val . ' ' . $time_val);
        if (false === ($this->_model->registerAdminprofile($request->getPost(), $default))) {
            return $this->render("addprofile");
        }

        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>Profile created successfully</strong>"));
        $this->_helper->redirector('listprofile');
    }

    public function updateprofileAction() {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $this->_helper->redirector('listprofile');
        }

        if (false === ($this->_model->updateAdminprofile($request->getPost()))) {
            return $this->render("editprofile");
        }

        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>Profile updated</strong>"));
        $this->_helper->redirector('listprofile');
    }

    public function listprofileAction() {
        $this->view->profiles = $this->_model->getAdminprofiles();
    }

    public function savepageAction() {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $this->_helper->redirector('index');
        }

        if (false === ($this->_model->registerAdminpage($request->getPost()))) {
            return $this->render("addpage");
        }
        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>Action created successfully</strong>"));
        $this->_helper->redirector('index');
    }

    // delete user
    public function deleteprofileAction() {
        $request = $this->getRequest();

        if (!$request->isPost() and (null === $this->_getParam('id'))) {
            $this->_helper->redirector('index');
        }

        if (isset($_POST['id'])) {
            $success = $this->_model->deleteAdminprofiles($_POST['id']);
        } else if ($this->_getParam('id')) {
            $success = $this->_model->deleteAdminprofileById($this->_getParam('id'));
        }
        if ($success) {
            $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>profile(s) deleted</strong>"));
        } else {
            $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>There was an error deleting the profile</strong>"));
        }
        $this->_helper->redirector('listprofile');
    }

    public function addprivilegesAction() {

        $moduleactionsarr = array();
        $modulesarr = array();
        $modules = $this->_model->getAdminModules();

        foreach ($modules as $module) {
            $modulesarr[] = $module->module;
            $actions = $this->_model->getModuleActions($module->module);
            $actionarr = array();
            foreach ($actions as $action) {
                $actionarr[] = $action;
            }
            $moduleactionsarr[$module->module] = $actionarr;
        }

        $this->view->profile = $this->_getParam('profile');
        $this->view->profileid = $this->_getParam('id');
        $this->view->adminPrivileges = $this->_model->getAdminpage2profileById($this->_getParam('id'));
       // var_dump($this->view->adminPrivileges);
     
        $this->view->modules = $this->_model->getAdminModules();
        
        $this->view->moduleactions = $moduleactionsarr;
           
    }

    public function updateprivilegeAction() {
        // var_dump($_POST['pageid']);
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('listprofile');
        }
        $profileid = $_POST['profileid'];
        //clear all profiles 
        $this->_model->deleteProfileById($profileid);
        foreach ($_POST['pageid'] as $pageid) {
            $data = array();
            $data['page_id'] = $pageid;
            $data['profile_id'] = $profileid;
            $this->_model->registerAdminpage2profile($data);
        }
        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>Profile updated successfully</strong>"));
        $this->_helper->redirector('listprofile');
    }

    public function getPageaddForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['pageadd'] = $this->_model->getForm("adminpageAdd");
        $this->_forms['pageadd']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "admin",
                    "action" => "savepage"
                        ), 'default'
                ));
        $this->_forms['pageadd']->setMethod('post');
        return $this->_forms['pageadd'];
    }

    public function getProfileaddForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['profileadd'] = $this->_model->getForm("adminprofileAdd");
        $this->_forms['profileadd']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "admin",
                    "action" => "saveprofile"
                        ), 'default'
                ));

        /* begin processing next primary key for roles table */
        $newroleid = "";
        $lastid = $this->_model->getLastid()->role_id;
        if ($lastid == "") {
            $input = 1;
            $newroleid = str_pad($input, 3, "0", STR_PAD_LEFT);
        } else {
            //increment last id
            $newroleid = $lastid + 1;
            if (strlen($lastid) == 1) {
                $newroleid = str_pad($newroleid, 3, "0", STR_PAD_LEFT);
            }
        }
        /* end process next primary key for roles table */
        $this->_forms['profileadd']->getElement('role_id')->setValue($newroleid);
        $this->_forms['profileadd']->setMethod('post');
        return $this->_forms['profileadd'];
    }

    public function getProfile_editForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['profile_edit'] = $this->_model->getForm("adminprofileEdit");
        $this->_forms['profile_edit']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "admin",
                    "action" => "updateprofile"
                        ), 'default'
                ));
        $this->_forms['profile_edit']->setMethod('post');
        return $this->_forms['profile_edit'];
    }

    public function getAdminuserAddForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['adminuserAdd'] = $this->_adminModel->getForm("adminuserAdd");
        $this->_forms['adminuserAdd']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "admin",
                    "action" => "saveuser"
                        ), 'default'
                ));
        $this->_forms['adminuserAdd']->setMethod('post');
        return $this->_forms['adminuserAdd'];
    }

    public function getAdminuserEditForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['adminuserEdit'] = $this->_adminModel->getForm("adminuserEdit");
        $this->_forms['adminuserEdit']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "admin",
                    "action" => "updateuser"
                        ), 'default'
                ));
        $this->_forms['adminuserEdit']->setMethod('post');
        return $this->_forms['adminuserEdit'];
    }

    //reset edit form for viewing details
    protected function resetAdminuserEditForm($form) {
        $urlHelper = $this->_helper->getHelper('url');
        $form->setAction(
                $urlHelper->url(
                        array(
                    'controller' => 'sms',
                    'action' => 'edit'
                        ), 'default'
                ));
        $formElements = $form->getElements();
        foreach ($formElements as $element) {
            if ($element->getName() == "sub") {
                continue;
            }
            $element->setAttrib('readonly', 'readonly');
        }

        // turn off description
        $form->setElementDecorators(array(
            'ViewHelper',
            'HtmlTag',
            'Label'
        ));

        //remove submit button
        $form->removeElement('sub');
    }
    
     public function unblockadminuserAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector("listadminuser");
        }
        $userIds = $_POST["userid"];
        foreach ($userIds as $userid) {
            $data = array();
            $data["user_account_disable"] = 0;
            $this->_adminModel->enableAdminuser($data, $userid);
        }
        $this->_flashMessenger->addMessage(array("alert alert-success" => "Admin user account(s) unblocked"));
        $this->_helper->redirector("listadminuser");
    }

    public function blockadminuserAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector("listadminuser");
        }
        $userIds = $_POST["userid"];
        foreach ($userIds as $userid) {
            $data = array();
            $data["user_account_disable"] = 1;
            $this->_adminModel->enableAdminuser($data, $userid);
        }
        $this->_flashMessenger->addMessage(array("alert alert-success" => "Admin user account(s) blocked"));
        $this->_helper->redirector("listadminuser");
    }

}

?>