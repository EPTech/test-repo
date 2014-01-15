<?php


class System_Resource_Carriageuses extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'carriage_usage';
    protected $_primary = 'id';
  
    public function getCarriageusageById($id) {
        return $this->find($id)->current();
    }

    public function getCarriageusages(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
  
    public function deleteCarriageusages(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteCarriageusageById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>