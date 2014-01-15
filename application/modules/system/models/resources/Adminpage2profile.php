<?php

require_once dirname(__FILE__) . '/Adminpage2profile/Item.php';

class System_Resource_Adminpage2profile extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'resources_to_roles';
    protected $_primary = array('role_id','resource_id');
    protected $_rowClass = "System_Resource_Adminpage2profile_Item";

    public function getUserById($id) {
        return $this->find($id)->current();
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
        return $this->fetchAll();
    }

    public function deleteUsers(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary.' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteProfileById($id) {
        $where = $this->getAdapter()->quoteInto('role_id = ?', $id);
        return $this->delete($where);
    }
    
    public function getAdminpage2profileById($id){
        $select = $this->select();
        $select->where("role_id = ?", $id);
        return $this->fetchAll($select);
    }
    
     public function getAdminpage2profileByPageId($id){
        $select = $this->select();
        $select->where("resource_id = ?", $id);
        return $this->fetchAll($select);
    }

}
