<?php

class System_UserController extends Zend_Controller_Action {

    protected $_model;
    protected $_staffmodel;
    protected $_authService;
    protected $_staffauthService;
    protected $_adminModel;

    public function init() {
        $this->_staffmodel = new System_Resource_Staff();
        $this->_staffauthService = new System_Service_Authenticatestaff($this->_staffmodel);

        $this->_model = new System_Model_User();
        $this->_authService = new System_Service_Authentication(new System_Resource_Mlos());
        //add forms
        $this->view->loginForm = $this->getLoginForm();
        $this->view->staffloginForm = $this->getStaffLoginForm();
        $this->view->userForm = $this->getUserForm();
        $this->view->useraddForm = $this->getUserAddForm();
        $this->view->adduser = $this->getAdminuserAddForm();
        $this->view->changepass = $this->getPasswordchangeForm();
        //init flashmessenger
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function loginAction() {
      //  $this->_helper->layout()->disableLayout();
      $this->_helper->layout()->setLayout("login");
        //add return path to login form
        //$loginLabel = new Zend_Session_Namespace();
        // $this->getLoginForm()->setDescription($loginLabel->label);
        $this->getLoginForm()->addElement('hidden', 'return', array(
            'value' => Zend_Controller_Front::getInstance()->getRequest()->getRequestUri(),
        ));
    }

    public function loginstaffAction() {
        //$this->_helper->layout()->disableLayout();
        //add return path to login form
        //$loginLabel = new Zend_Session_Namespace();
        // $this->getLoginForm()->setDescription($loginLabel->label);
       //$this->getStaffLoginForm()->setAction('/system/user/authenticatestaff');
            $this->_helper->layout()->setLayout("login");
        $this->getStaffLoginForm()->addElement('hidden', 'return', array(
            'value' => Zend_Controller_Front::getInstance()->getRequest()->getRequestUri(),
        ));
    }

    public function changePassAction() {
        $this->_helper->layout()->disableLayout();
    }

    public function renewpasswordAction() {
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('login');
        }
        $user = $this->_model->getUserByUsername($_POST['username']);
        if ($user !== null) {
            if ($user->user_account_lock == 1 or $user->user_account_disable == 1) {
                $this->_helper->redirector('accountblock');
                exit;
            }
            $form = $this->_forms['passchange'];
            if (!$form->isValid($request->getPost())) {
                return $this->render('change-pass');
            }
            $this->_adminModel = new System_Model_Adminuser();
            $this->_adminModel->updatePassword($form, $request->getPost());
            $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>Password has being changed sucessfully. Enter your credentials to login</strong>"));
            $this->_helper->redirector('index');
        }
        $this->_flashMessenger->addMessage(array('alert alert-warning' => "<strong>Invalid login credentials</strong>"));
        $this->_helper->redirector('index');
        //$this->_helper->getHelper('viewrenderer')->setNoRender();
        exit;
    }

    public function accountblockAction() {
        $this->_helper->layout()->disableLayout();
    }

    public function adduserAction() {
        
    }

    public function saveuserAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('index');
        }

        //validate
        $form = $this->_forms['adminuserAdd'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('adduser');
        }

        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>User created sucessful</strong>"));
        $this->_helper->redirector('index');
    }

    public function authenticateAction() {
       // $this->_helper->layout()->disableLayout();
          $this->_helper->layout()->setLayout("login");
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('login');
        }

        //validate
        $form = $this->_forms['login'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }

        if (false === $this->_authService->authenticate($form->getValues())) {
            $loginLabel = new Zend_Session_Namespace();
            $form->setDescription($loginLabel->label);
            return $this->render('login');
        }



        // $redirectUrl = ($this->_request->getPost('return') == "/system/user/login") ? "/system/index/index" : $this->_request->getPost('return');
        $redirectUrl = $this->_request->getPost('return');
        //var_dump($redirectUrl); exit;
        if ($redirectUrl == "/system/user/login") {
            $redirectUrl = "/default/profiles/";
        } else if ($redirectUrl == "/system/user/authenticate") {
            $redirectUrl = "/default/profiles/";
        } else if ($redirectUrl == "/system/index/index") {
            $redirectUrl = "/default/profiles/";
        }

        if(is_null($redirectUrl)){
             $redirectUrl = "/default/profiles/";
        }
        //var_dump($redirectUrl); die;
        return $this->_redirect($redirectUrl);
    }

    public function authenticatestaffAction() {
       // $this->_helper->layout()->disableLayout();
            $this->_helper->layout()->setLayout("login");
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('loginstaff');
        }

        //validate
        $form = $this->_forms['stafflogin'];
        if (!$form->isValid($request->getPost())) {
            return $this->render('loginstaff');
        }

       // var_dump($form->getValues()); die;
        if (false === $this->_staffauthService->authenticate($form->getValues())) {
            $loginLabel = new Zend_Session_Namespace();
            $form->setDescription($loginLabel->label);
            return $this->render('loginstaff');
        }



        // $redirectUrl = ($this->_request->getPost('return') == "/system/user/login") ? "/system/index/index" : $this->_request->getPost('return');
        $redirectUrl = $this->_request->getPost('return');
        //var_dump($redirectUrl); exit;
        if ($redirectUrl == "/system/user/loginstaff") {
            $redirectUrl = "/default/profiles/index";
        } else if ($redirectUrl == "/system/user/authenticate") {
            $redirectUrl = "/system/admin/";
        } else if ($redirectUrl == "/system/index/index") {
            $redirectUrl = "/default/profiles/index";
        }

         if(is_null($redirectUrl)){
             $redirectUrl = "/default/profiles/";
        }
        
       // die($redirectUrl);

        return $this->_redirect($redirectUrl);
    }

    public function logoutAction() {
        $this->_helper->layout()->disableLayout();
        $this->_authService->clear();
        $this->_staffauthService->clear();
        $this->_helper->redirector->gotoUrl('/system/user/login');
    }

    public function logoutstaffAction() {
        $this->_helper->layout()->disableLayout();
        $this->_staffauthService->clear();
        $this->_helper->redirector->gotoUrl('/system/user/loginstaff');
    }

    public function indexAction() {
        $this->view->users = $this->_model->getUsers($this->_getParam('page', 1));
    }

    public function saveAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('index');
        }

        if (false === ($this->_model->saveUser($request->getPost()))) {
            return $this->render('edit');
        }
        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>Update sucessful</strong>"));
        $this->_helper->redirector('index');
    }

    public function saveadminAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector('index');
        }

        if (false === ($this->_model->registerAdmin($request->getPost()))) {
            return $this->render("add");
        }
        $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>user added</strong>"));
        $this->_helper->redirector('index');
    }

    // delete user
    public function deleteAction() {
        $request = $this->getRequest();

        if (!$request->isPost() and (null === $this->_getParam('id'))) {
            $this->_helper->redirector('index');
        }

        if (isset($_POST['id'])) {
            $success = $this->_model->deleteUsers($_POST['id']);
        } else if ($this->_getParam('id')) {
            $success = $this->_model->deleteUserById($this->_getParam('id'));
        }
        if ($success) {
            $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>user(s) deleted</strong>"));
        } else {
            $this->_flashMessenger->addMessage(array('alert alert-success' => "<strong>There was an error deleting user</strong>"));
        }
        $this->_helper->redirector('index');
    }

    public function editAction() {
        $userId = $this->_getParam('id', 1); //will be from session
        /*
          if ($this->_authService->getIdentity()->role_id != "Admin") {
          $userId = $this->_authService->getIdentity()->id;
          }
         * 
         */
        // var_dump($this->_authService->getIdentity()->id); die;
        $this->view->user = $this->_model->getUserById($userId);
        if (null === $this->view->user) {
            throw new PP_Exception_404('Unknown Userparameter ' . $this->_getParam('id'));
        }
        $this->view->userForm = $this->getUserForm()->populate(
                $this->view->user->toArray()
        );
    }

    public function addAction() {
        //   if ($this->_authService->getIdentity()->role_id != "Admin") {
        //       throw new PP_Exception_404('Access Denied ' . $this->_getParam('id'));
        // }
    }

    public function registerAction() {
        
    }

    public function completeRegistrationAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->_helper->redirector("register");
        }

        if (false === ($this->_model->registerUser($request->getPost()))) {

            return $this->render("register");
        }
    }

    public function getRegistrationForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['register'] = $this->_model->getForm("userRegister");
        $this->_forms['register']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "user",
                    "action" => "complete-registration"
                        ), 'default'
                ));
        $this->_forms['register']->setMethod('post');
        return $this->_forms['register'];
    }

    public function getLoginForm() {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['login'] = $this->_model->getForm('userLogin');
        $this->_forms['login']->setAction($urlHelper->url(array(
                    'module' => 'system',
                    'controller' => 'user',
                    'action' => 'authenticate',
                        ), 'default'
                ));
        $this->_forms['login']->setMethod('post');

        return $this->_forms['login'];
    }

    public function getStaffLoginForm() {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['stafflogin'] = $this->_model->getForm('userStafflogin');
        $this->_forms['stafflogin']->setAction($urlHelper->url(array(
                    'module' => 'system',
                    'controller' => 'user',
                    'action' => 'authenticatestaff',
                        ), 'default'
                ));
        $this->_forms['stafflogin']->setMethod('post');

        return $this->_forms['stafflogin'];
    }

    public function getUserForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['userEdit'] = $this->_model->getForm('userEdit');
        $this->_forms['userEdit']->setAction($urlHelper->url(
                        array(
                    'module' => 'system',
                    "controller" => "user",
                    "action" => "save"
                        ), "default"
                ));
        $this->_forms['userEdit']->setMethod('post');
        return $this->_forms['userEdit'];
    }

    public function getUserAddForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['userAdd'] = $this->_model->getForm('userAdd');
        $this->_forms['userAdd']->setAction($urlHelper->url(
                        array(
                    'module' => 'system',
                    "controller" => "user",
                    "action" => "saveadmin"
                        ), "default"
                ));
        $this->_forms['userAdd']->setMethod('post');
        return $this->_forms['userAdd'];
    }

    public function denyaccessAction() {
        
    }

    public function getAdminuserAddForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['adminuserAdd'] = $this->_model->getForm("userBases");
        $this->_forms['adminuserAdd']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "user",
                    "action" => "saveuser"
                        ), 'default'
                ));
        $this->_forms['adminuserAdd']->setMethod('post');
        return $this->_forms['adminuserAdd'];
    }

    public function getPasswordchangeForm() {
        $urlHelper = $this->_helper->getHelper("url");
        $this->_forms['passchange'] = $this->_model->getForm("userPasswordchange");
        $this->_forms['passchange']->setAction(
                $urlHelper->url(array(
                    'module' => 'system',
                    "controller" => "user",
                    "action" => "renewpassword"
                        ), 'default'
                ));
        $this->_forms['passchange']->setMethod('post');
        return $this->_forms['passchange'];
    }

}

?>