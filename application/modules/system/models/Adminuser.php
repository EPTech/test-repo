<?php

class System_Model_Adminuser extends PP_Model_Acl_Abstract {

    protected $_acl;

    public function getResourceId() {
        return 'User';
    }

    public function getAllCountries() {
        return $this->getResource('Countries')->getAllCountries();
    }

    public function getAdminprofileByName($name) {
        return $this->getResource('Adminprofile')->getAdminprofileByName($name);
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

    public function getUserDetailsById($id) {
        $id = (int) $id;
        return $this->getResource('Adminuser')->getUserDetailsById($id);
    }

    public function updateUserProperties($data, $userid) {
        $where = $this->getResource('Adminuser')->getAdapter()->quoteInto('user_id = ?', $userid);
        return $this->getResource('Adminuser')->update($data, $where);
    }

    public function getUserByEmail($email, $ignoreUser = null) {
        return $this->getResource('User')->getUserByEmail($email, $ignoreUser);
    }

    public function getUserByUsername($username, $ignoreUser = null) {
        return $this->getResource('Adminuser')->getUserByUsername($username, $ignoreUser);
    }

    public function getUserByPassword($pass) {
        return $this->getResource('Adminuser')->getUserByPassword($pass);
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

        return $this->getResource("Adminuser")->getUsers($paged, $order);
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

    public function getParameter($name) {
        return $this->getResource('Parameter')->getParameterByname($name);
    }

    public function registerAdmin($post) {
        $form = $this->getForm('adminuserAdd');
        $date = new Zend_Date();
        $default_expiry_days = $this->getParameter('password_expiry_days')->parameter_value;
        //    echo 'expiry date is '.$default_expiry_days.'<br/> '; exit;
        $date_val = $date->get(Zend_Date::YEAR) . "-" . $date->get(Zend_Date::MONTH) . "-" . $date->get(Zend_Date::DAY);
        $time_val = $date->get(Zend_Date::HOUR_AM) . ":" . $date->get(Zend_Date::MINUTE_SHORT) . ":" . $date->get(Zend_Date::SECOND_SHORT);
        $register_date = Zend_Date::now();
        $pass_expiry_date_zend = $register_date->addDay($default_expiry_days);
        $pass_expiry_date = $pass_expiry_date_zend->get(Zend_Date::YEAR) . "-" . $pass_expiry_date_zend->get(Zend_Date::MONTH) . "-" . $pass_expiry_date_zend->get(Zend_Date::DAY);

        //return $this->_save($form, $post, array("role_id" => "Admin", 'status' => 'active'));
        return $this->_save($form, $post, array('created' => $date_val . ' ' . $time_val, 'user_pass_expiry_date' => $pass_expiry_date));
    }

    public function saveadminUser($post) {
        $form = $this->getForm('adminuserEdit');
        $date = new Zend_Date();
        $date_val = $date->get(Zend_Date::YEAR) . "-" . $date->get(Zend_Date::MONTH) . "-" . $date->get(Zend_Date::DAY);
        $time_val = $date->get(Zend_Date::HOUR_AM) . ":" . $date->get(Zend_Date::MINUTE_SHORT) . ":" . $date->get(Zend_Date::SECOND_SHORT);
        return $this->_save($form, $post, array('modified' => $date_val . ' ' . $time_val,));
    }

    public function updatePassword($form, $info, $defaults = array()) {
        $data = $form->getValues();
        if (array_key_exists('user_password', $data) && '' != $data['user_password']) {
            $data['salt'] = md5($this->createSalt());
            $data['user_password'] = md5($data['user_password'] . $data['salt']);
        }
        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        $user = $this->getResource('Adminuser')->getUserByUsername($data['username']);
        $data['last_used_passwords'] = $user->last_used_passwords;

        //recalculate expiry date

        $default_expiry_days = $this->getParameter('password_expiry_days')->parameter_value;
        //    echo 'expiry date is '.$default_expiry_days.'<br/> '; exit;
        $register_date = Zend_Date::now();
        $pass_expiry_date_zend = $register_date->addDay($default_expiry_days);
        $pass_expiry_date = $pass_expiry_date_zend->get(Zend_Date::YEAR) . "-" . $pass_expiry_date_zend->get(Zend_Date::MONTH) . "-" . $pass_expiry_date_zend->get(Zend_Date::DAY);
        //end recalculate expiry date
        $data['user_password_status'] = 0;
        $data['user_pass_expiry_date'] = $pass_expiry_date;
        //unset($data['username']);

        $cols = $this->getResource('Adminuser')->getAllColumns();

        // $data array contains keys that are not columns in the table hence the need for new array
        $newData = array();

        foreach ($cols as $col) {
            //if  exist in table 
            if (key_exists($col, $data) and isset($data[$col])) {
                $newData[$col] = $data[$col];
            }
        }

        $where = $this->getResource('Adminuser')->getAdapter()->quoteInto("username =  ? ", $newData['username']);
        return $this->getResource('Adminuser')->update($newData, $where);
    }

    public function _createMerchantUser(array $info, $defaults = array()) {


        $data = $info;
        //password hashing
        if (array_key_exists('user_password', $data) && '' != $data['user_password']) {
            $data['user_salt'] = md5($this->createSalt());
            $data['user_password'] = md5($data['user_password'] . $data['user_salt']);
            $data['salt'] = $data['user_salt'];
        } else {
            unset($data['user_password']);
        }

        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        //check some fields
        if (key_exists('override_wh', $data)) {
            if ($data['override_wh'] == 0) {
                unset($data['extend_wh']);
            }
        }

        $data['last_used_passwords'] = $data['user_password'];
        $user = array_key_exists('user_id', $data) ? $this->getResource('Adminuser')->getUserById($data['user_id']) : null;

        return $this->getResource('Adminuser')->saveRow($data, $user);
    }

    protected function _save(Zend_Form $form, array $info, $defaults = array()) {
        if (!$form->isValid($info)) {
            return false;
        }

        $data = $form->getValues();

        //password hashing
        if (array_key_exists('user_password', $data) && '' != $data['user_password']) {
            $data['salt'] = md5($this->createSalt());
            $data['user_password'] = md5($data['user_password'] . $data['salt']);
        } else {
            unset($data['user_password']);
        }

        //apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        //check some fields
        if ($data['override_wh'] == 0) {
            unset($data['extend_wh']);
        }


        $data['last_used_passwords'] = $data['user_password'];
        $user = array_key_exists('user_id', $data) ? $this->getResource('Adminuser')->getUserById($data['user_id']) : null;

        return $this->getResource('Adminuser')->saveRow($data, $user);
    }

    private function createSalt() {
        $salt = "";
        for ($i = 0; $i < 50; $i++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }

    public function deleteUserById($id) {
        return $this->getResource('Adminuser')->deleteUserById($id);
    }

    public function deleteUsers(array $ids) {
        return $this->getResource('Adminuser')->deleteUsers($ids);
    }

    public function enableAdminuser($data, $userid) {
        $user = $this->getResource('Adminuser')->getUserById($userid);
        return $this->getResource('Adminuser')->saveRow($data, $user);
    }

}
