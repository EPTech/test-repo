<?php

//require_once dirname(__FILE__) . '/Lgas/Item.php';

class Default_Resource_Lgas extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'lgas';
    protected $_primary = 'id';
  
    public function getLgaById($id) {
        return $this->find($id)->current();
    }

    public function getLgas(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function getLgaByState($stateid){
        $select = $this->select();
        $select->where('state_id = ?', $stateid)
                ->order('name');
        return $this->fetchAll($select);
    }
    
    
    public function deleteLgas(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteLgaById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>