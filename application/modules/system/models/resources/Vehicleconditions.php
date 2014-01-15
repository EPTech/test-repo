<?php

//require_once dirname(__FILE__) . '/Customers/Item.php';

class System_Resource_Vehicleconditions extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'vehicle_conditions';
    protected $_primary = 'condition_id';
    //protected $_rowClass = "Default_Resource_Customers_Item";
    
    public function getVehicleconditionById($id) {
        return $this->find($id)->current();
    }

    public function getVehicleconditions(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function deleteVehiclecondition(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteVehicleconditionsById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>