<?php
class System_Model_User extends PP_Model_Acl_Abstract {

    protected $_acl;

    public function getResourceId() {
        return 'User';
    }

    public function setAcl(PP_Acl_Interface $acl) {
        /*
          if (!$acl->has($this->getResourceId())) {
          $acl->add($this)
          ->allow('Guest', $this, array('register'))
          ->allow('Customer', $this, array('saveUser'))
          // ->deny('Customer',$this,array('listUser'))
          ->allow('Admin', $this);
          }
          $this->_acl = $acl;
          return $this;
         * 
         */
    }

    public function getAcl() {
        /*
          if (null === $this->_acl) {
          $this->setAcl(new System_Model_Acl_System());
          }
          return $this->_acl;
         * 
         */
    }

    public function getUserById($id) {
        $id = (int) $id;
        return $this->getResource('User')->getUserById($id);
    }

    public function getUserByEmail($email, $ignoreUser = null) {
        return $this->getResource('User')->getUserByEmail($email, $ignoreUser);
    }
    
    public function getUserByUsername($username, $ignoreUser = null) {
        return $this->getResource('User')->getUserByUsername($username, $ignoreUser);
    }

    public function getUsers($paged = false, $order = null) {
        //if user is not admin pullout only their record
        //  if(Zend_Auth::getInstance()->getIdentity()->role_id != "Admin")
        //  {
        //return $this->getUserByEmail(Zend_Auth::getInstance()->getIdentity()->email);
        //  }  
        /*
          if (!$this->checkAcl('listUser')) {
          //  throw new RM_Acl_Exception("Insufficient rights");
          }
         * 
         */

        return $this->getResource("Adminuser")->getUsers();
    }

    public function registerUser($post) {
        /*
          if (!$this->checkAcl('register')) {
          throw new PP_Acl_Exception("Insufficient rights");
          }
         * 
         */
        $form = $this->getForm('userRegister');
        return $this->_save($form, $post, array("role_id" => "guest"));
    }

    public function registerAdmin($post) {
        // if (!$this->checkAcl('register')) {
        //     throw new PP_Acl_Exception("Insufficient rights");
        // }
        $form = $this->getForm('userAdd');

        return $this->_save($form, $post, array("role_id" => "Admin", 'status' => 'active'));
    }

    public function saveUser($post) {
        /*
          if (!$this->checkAcl('saveUser')) {
          throw new PP_Acl_Exception("Insufficient rights");
          }
         * 
         */
        $form = $this->getForm("userEdit");

        return $this->_save($form, $post);
    }

     public function _createMerchantUser( array $info, $defaults = array()) {
       

        $data = $info();

        //password hashing
        if (array_key_exists('password', $data) && '' != $data['password']) {
            $data['salt'] = md5($this->createSalt());
            $data['password'] = md5($data['password'] . $data['salt']);
        } else {
            unset($data['password']);
        }

        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        $user = array_key_exists('id', $data) ? $this->getResource('User')->getUserById($data['id']) : null;

        return $this->getResource('User')->saveRow($data, $user);
    }

    
    
    protected function _save(Zend_Form $form, array $info, $defaults = array()) {
        if (!$form->isValid($info)) {
            // exit("i am here");
            return false;
        }

        $data = $form->getValues();

        //password hashing
        if (array_key_exists('password', $data) && '' != $data['password']) {
            $data['salt'] = md5($this->createSalt());
            $data['password'] = md5($data['password'] . $data['salt']);
        } else {
            unset($data['password']);
        }

        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        $user = array_key_exists('id', $data) ? $this->getResource('User')->getUserById($data['id']) : null;

        return $this->getResource('User')->saveRow($data, $user);
    }

    private function createSalt() {
        $salt = "";
        for ($i = 0; $i < 50; $i++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }

    public function deleteUserById($id) {
        return $this->getResource('User')->deleteUserById($id);
    }

    public function deleteUsers(array $ids) {
        return $this->getResource('User')->deleteUsers($ids);
    }

}
