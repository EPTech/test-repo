<?php

//require_once dirname(__FILE__) . '/Mvregister/Item.php';

class Default_Resource_Mvregister extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'mvregister';
    protected $_primary = array('mvreg_id','mvregno');
   // protected $_rowClass = "Default_Resource_Customers_Item";

    public function getMvregisterById($id) {
        return $this->find($id)->current();
    }

    public function getMvregisterByMvregno($mvregno){
        $select = $this->select();
        $select->where('mvregno = ?', $mvregno);
             return $this->fetchRow($select);
    }
    
     public function getListing() {
        $select = $this->select();
        return $select;
    }

   
    public function deleteMvregisters(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteMvregisterById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>