<?php


class Default_Resource_Charges extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'charges';
    protected $_primary = 'charge_id';
  
    public function getChargeById($id) {
       // return $this->find($id)->current();
        $select = $this->select(true)->setIntegrityCheck(false);
       $select->joinInner('charge_cycles','charge_cycles.charge_cycle_id = charges.charge_cycle', array('charge_cycle_id','charge_cycle_title','charge_cycle_addition_string'));
       $select->where("charge_id = ?", $id);
       return $this->fetchRow($select);
       
    }

    public function getCharges(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
  public function getListing(){
      
       $select = $this->select(true)->setIntegrityCheck(false);
       $select->joinInner('charge_cycles','charge_cycles.charge_cycle_id = charges.charge_cycle', array('charge_cycle_id','charge_cycle_title'));
       $select->order('charge_id');
       return $select;
  }
    public function deleteCharges(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteChargeById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>