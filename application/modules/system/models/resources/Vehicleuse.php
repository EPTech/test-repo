<?php

//require_once dirname(__FILE__) . '/Customers/Item.php';

class System_Resource_Vehicleuse extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'vehicle_uses';
    protected $_primary = 'vehicle_use_id';
    //protected $_rowClass = "Default_Resource_Customers_Item";
    

    public function getVehicleuseById($id) {
        return $this->find($id)->current();
    }

    public function getVehicleuses(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function deleteVehicleuses(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteVehicleuseById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>