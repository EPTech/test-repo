<?php

require_once dirname(__FILE__) . '/Adminprofile/Item.php';

class System_Resource_Adminprofile extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'roles';
    protected $_primary = 'role_id';
    protected $_rowClass = "System_Resource_Adminprofile_Item";

    public function getAdminprofileById($id) {
        return $this->find($id)->current();
    }

      public function  getAdminprofileByName($name){
      $select = $this->select();
      $select->where("role_name = ?", $name);
      return $this->fetchRow($select);
  }
  
    public function getLastid() {
        $select = $this->select()
                  ->order('role_id desc')
                ->limit(1);
        return $this->fetchRow($select);
    }

    public function getAdminprofiles($paged = false, $order = null) {
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

    public function getaclAdminprofiles() {
        $select = $this->select();
        return $this->fetchAll($select);
    }

    public function deleteAdminprofiles(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto( 'role_id = ?', $val);
            $this->delete($where);
        }
        return true;
    }
       public function deleteAdminprofileById($id) {
        $where = $this->getAdapter()->quoteInto('role_id = ?', $id);
        return $this->delete($where);
    }

}
