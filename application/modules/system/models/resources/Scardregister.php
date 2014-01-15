<?php

//require_once dirname(__FILE__) . '/Mvregister/Item.php';

class Default_Resource_Scardregister extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'scardregister';
    protected $_primary = array('scard_id','scardno');
   // protected $_rowClass = "Default_Resource_Customers_Item";

    public function getScardregisterById($id) {
        return $this->find($id)->current();
    }

    public function getScardregisterByScardno($scardno){
        $select = $this->select();
        $select->where('scardno = ?', $scardno);
             return $this->fetchRow($select);
    }
    
     public function getListing() {
        $select = $this->select();
        return $select;
    }

   
    public function deleteScardregisters(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteScardregisterById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>