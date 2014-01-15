<?php

require_once dirname(__FILE__) . '/User/Item.php';

class System_Resource_User extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'admin_users';
    protected $_primary = 'customer_id';
    protected $_rowClass = "System_Resource_User_Item";

    public function getUserById($id) {
        return $this->find($id)->current();
    }

    public function getUserByEmail($email, $ignoreUser = null) {
        $select = $this->select()->setIntegrityCheck(false);
           $select = $select->from("admin_users", "admin_users.*")
         ->joinLeft('roles', ' roles.role_id = admin_users.role_id', array("role_name"))
        ->where('username = ?', $email);
        return $this->fetchRow($select);
    }

     public function getUserByUsername($username, $ignoreUser = null) {
        $select = $this->select();
        $select->where('username = ?', $username);
        if ($ignoreUser !== null) {
            $select->where('username != ?', $username);
        }
        //die($select); 
        return $this->fetchRow($select);
    }

    
    public function getUsers($paged = false, $order = null) {
        $select = $this->select();
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
    }

    public function deleteUsers(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary.' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteUserById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary.' = ?', $id);
        return $this->delete($where);
    }

}
