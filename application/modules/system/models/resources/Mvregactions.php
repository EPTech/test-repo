<?php

//require_once dirname(__FILE__) . '/Lgas/Item.php';

class Default_Resource_Mvregactions extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'mvreg_actions';
    protected $_primary = 'action_id';
  
    public function getMvregactionById($id) {
        return $this->find($id)->current();
    }

    public function getMvregactions(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
   
    
    public function deleteMvregactions(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteMvregactionById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>