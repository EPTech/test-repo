<?php


class Default_Resource_Chargecycles extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'charge_cycles';
    protected $_primary = 'charge_cycle_id';
  
    public function getChargecycleById($id) {
        return $this->find($id)->current();

    }

    public function getChargecycles(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
  
    public function deleteChargecycles(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteChargecycleById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>