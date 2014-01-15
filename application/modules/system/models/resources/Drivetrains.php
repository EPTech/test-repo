<?php

//require_once dirname(__FILE__) . '/Customers/Item.php';

class System_Resource_Drivetrains extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'drive_trains';
    protected $_primary = 'id';
    //protected $_rowClass = "Default_Resource_Customers_Item";

    public function getDrivetrainById($id) {
        return $this->find($id)->current();
    }

    public function getDrivetrains(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function deleteDrivetrains(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteDrivetrainById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>