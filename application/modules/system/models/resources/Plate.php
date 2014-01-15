<?php

//require_once dirname(__FILE__) . '/States/Item.php';

class System_Resource_Plate extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'platenumbers';
    protected $_primary = 'pid';

    public function getPlateById($id) {
        return $this->find($id)->current();
//        $select = $this->select(true)->setIntegrityCheck(false);
//        $select->joinLeft('states', 'states.state_id = mla.state', array('state_name' => 'state'));
//        $select->joinLeft('roles', 'roles.role_id = mla.role_id', array('role_name'));
//        $select->joinLeft('mlo', 'mlo.mla_id = mla.mla_id', array('mlo_name', 'mlo_state_name' => new Zend_Db_Expr("(Select states.state from states where states.state_id = mlo.state)"), 'mlo_lga_name' => new Zend_Db_Expr("(Select lgas.name from lgas where lgas.id = mlo.lga)")));
//        $select->joinLeft('lgas', 'mla.lga = lgas.id', array('name'));
//        $select->where("mla.mla_id = ?", $id);
//        //die($select);
//        return $this->fetchRow($select);
    }

    public function getListing() {
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('plate_categories', 'plate_categories.cat_id = platenumbers.pcategory', array('category'));
        $select->joinLeft('lgas', 'platenumbers.lga = lgas.id', array('name'));
         $select->joinLeft('staff', 'staff.staff_id = platenumbers.staff', array('surname','firstname'));
        $select->joinLeft('mlo', 'mlo.mlo_id = platenumbers.mlo', array('mlo_name', 'mlo_state_name' => new Zend_Db_Expr("(Select states.state from states where states.state_id = mlo.state)"), 'mlo_lga_name' => new Zend_Db_Expr("(Select lgas.name from lgas where lgas.id = mlo.lga)")));
        //die($select);
        return $select;
    }

    public function getPlatenumbers() {
        $select = $this->select();
        return $this->fetchAll($select);
    }

    public function deletePlatenumbers(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deletePlatenumberById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>