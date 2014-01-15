<?php

//require_once dirname(__FILE__) . '/Customers/Item.php';

class System_Resource_Manufacturers extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'manufacturers';
    protected $_primary = 'id';
    //protected $_rowClass = "Default_Resource_Customers_Item";

    public function getManufacturerById($id) {
        return $this->find($id)->current();
    }

    public function getManufacturers(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function deleteManufacturers(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteManufacturerById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>