<?php
require_once dirname(__FILE__) . '/User/Item.php';

class System_Resource_Parameter extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'parameters';
    protected $_primary = 'parameter_id';
    protected $_rowClass = "System_Resource_Parameter_Item";

    public function getUserById($id) {
        return $this->find($id)->current();
    }

    public function getParameterByname($name) {
        $select = $this->select();
        $select->where('parameter_name = ?', $name);
        return $this->fetchRow($select);
    }

     public function getUserByUsername($username, $ignoreUser = null) {
        $select = $this->select();
        $select->where('username = ?', $username);
        if ($ignoreUser !== null) {
            $select->where('username != ?', $username);
        }
        return $this->fetchRow($select);
    }

    
    public function getUsers() {
        $select = $this->select();
        // return $this->fetchAll($select);
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
