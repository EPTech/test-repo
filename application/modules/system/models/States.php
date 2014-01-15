<?php

//require_once dirname(__FILE__) . '/States/Item.php';

class Default_Resource_States extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'states';
    protected $_primary = 'state_id';
   

    public function getStateById($id) {
        return $this->find($id)->current();
    }

    public function getStates(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function deleteStates(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteStateById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>