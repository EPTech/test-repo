<?php

//require_once dirname(__FILE__) . '/Customers/Item.php';

class System_Resource_Vehicletypes extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'vehicle_types';
    protected $_primary = 'id';
    //protected $_rowClass = "Default_Resource_Customers_Item";
    

    public function getVehicletypeById($id) {
        return $this->find($id)->current();
    }

    public function getVehicletypes(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function deleteVehicletypes(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteVehicletypeById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>