<?php
require_once dirname(__FILE__) . '/Adminpage/Item.php';

class System_Resource_Adminpage extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'resources';
    protected $_primary = 'resources_id';
    protected $_rowClass = "System_Resource_Adminpage_Item";

    public function getAdminpageById($id) {
        return $this->find($id)->current();
    }

  public function  getAdminpageByContnAct($controller, $action){
      $select = $this->select();
      $select->where("controller = ?", $controller)
              ->where("action = ?", $action);
      return $this->fetchAll($select);
  }
    public function getAdminpages($paged = false, $order = null) {
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

    public function getallAdminpages(){
        return $this->fetchAll();
    }
    
    public function getAdminModules(){
        $select = $this->select();
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::FROM);
        $select->from($this->_name, new Zend_Db_Expr("DISTINCT(module) AS `module`"));
        return $this->fetchAll($select);
    }
    
   public function getAdminControllers(){
        $select = $this->select();
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::FROM);
        $select->from($this->_name, new Zend_Db_Expr("DISTINCT(controller) AS `controller`"));
        return $this->fetchAll($select);
   }
    public function getModuleActions($module){
        $select = $this->select();
        $select->where("module = ?", $module);
        return $this->fetchAll($select);
    }
    public function deleteAdminpages(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary.' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteAdminpageById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary.' = ?', $id);
        return $this->delete($where);
    }

}
