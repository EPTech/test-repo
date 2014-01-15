<?php

require_once dirname(__FILE__) . '/Customers/Item.php';

class Default_Resource_Customers extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'customers';
    protected $_primary = 'mvregno';
    protected $_rowClass = "Default_Resource_Customers_Item";

    public function getCustomerById($id) {
        return $this->find($id)->current();
    }

     public function getListing() {
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinInner('states', "states.state_id = customers.state", array('state_name' => 'state'))
                ->joinInner('lgas', "lgas.id = customers.lga", array('lga_name' =>'name'));

        return $select;
    }

   
    public function deleteCustomers(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteCustomerById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>