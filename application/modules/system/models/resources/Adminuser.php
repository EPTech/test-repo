<?php

require_once dirname(__FILE__) . '/Adminuser/Item.php';

class System_Resource_Adminuser extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'admin_users';
    protected $_primary = 'user_id';
    protected $_rowClass = "System_Resource_Adminuser_Item";

    public function getUserById($id) {
        return $this->find($id)->current();
    }

    public function getUserDetailsById($id) {
        $select = $this->select()->setIntegrityCheck(false);
        $select = $select->from('admin_users', "admin_users.*")
                ->joinInner('countries', ' admin_users.countries_id=countries.countries_id', 'countries_name')
                ->joinInner('roles', 'admin_users.role_id = roles.role_id', 'role_name')
                ->where('admin_users.user_id = ? ', $id);

        return $this->fetchRow($select);
    }

    public function getUserByEmail($email, $ignoreUser = null) {
        $select = $this->select()->setIntegrityCheck(false);
        $select = $select->from("admin_users", "admin_users.*")
                ->joinLeft('roles', ' roles.role_id = admin_users.role_id', array("role_name"))
                ->where('username = ?', $email);
        //  die($select);
        return $this->fetchRow($select);
    }

    public function getUserByPassword($pass) {
        $select = $this->select();
        $select->where('user_password = ?', $pass);
        // die($select);
        return $this->fetchRow($select);
    }

    public function getUserByUsername($username) {
        $select = $this->select()->setIntegrityCheck(false);
        $select = $select->from('admin_users', "admin_users.*")
                ->joinInner('roles', 'admin_users.role_id = roles.role_id', 'role_name');
        $select->where('username = ?', $username);
        // die($select);
        return $this->fetchRow($select);
    }

    public function getUsers() {
        $select = $this->select();
        // return $this->fetchAll($select);
        $select = $this->select()->setIntegrityCheck(false);
        $select = $select->from('admin_users', "admin_users.*")
                ->joinInner('countries', ' admin_users.countries_id=countries.countries_id', 'countries_name')
                ->joinInner('roles', 'admin_users.role_id = roles.role_id', 'role_name');

        return $select;
        /*
          if (true === is_array($order)) {
          $select->order($order);
          }
          if (null !== $paged) {
          $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
          $count = clone $select;
          $count->reset(Zend_Db_Select::COLUMNS);
          $count->reset(Zend_Db_Select::FROM);
          $count->from($this->_name, new Zend_Db_Expr("COUNT(*) AS `zend_paginator_row_count`"));
          $adapter->setRowCount($count);
          $paginator = new Zend_Paginator($adapter);
          $paginator->setItemCountPerPage(15)
          ->setCurrentPageNumber((int) $paged);
          return $paginator;
          }
          var_dump($this->fetchAll()); exit;
          return $this->fetchAll();
         * 
         */
    }

    public function deleteUsers(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteUserById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}
