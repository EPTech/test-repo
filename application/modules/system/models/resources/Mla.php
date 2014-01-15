<?php

//require_once dirname(__FILE__) . '/States/Item.php';

class System_Resource_Mla extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'mla';
    protected $_primary = 'mla_id';

   
    public function getMlaById($id) {
        // return $this->find($id)->current();
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('states', 'states.state_id = mla.state', array('state_name' => 'state'));
        $select->joinLeft('roles', 'roles.role_id = mla.role_id', array('role_name'));
        $select->joinLeft('mlo', 'mlo.mla_id = mla.mla_id', array('mlo_name', 'mlo_state_name' => new Zend_Db_Expr("(Select states.state from states where states.state_id = mlo.state)"), 'mlo_lga_name' => new Zend_Db_Expr("(Select lgas.name from lgas where lgas.id = mlo.lga)")));
        $select->joinLeft('lgas', 'mla.lga = lgas.id', array('name'));
        $select->where("mla.mla_id = ?", $id);
        //die($select);
        return $this->fetchRow($select);
    }

    public function getMlaByTerm($term) {
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('states', 'states.state_id = mla.state', array('state_name' => 'state'));
        $select->joinLeft('lgas', 'mla.lga = lgas.id', array('name'));
        $select->where("mla_firstname like ?", '%' . $term . '%');
        $select->OrWhere("mla_lastname like ?", '%' . $term . '%');
        $select->OrWhere("states.state like ?", '%' . $term . '%');
        $select->OrWhere("lgas.name like ?", '%' . $term . '%');
        $select->order('mla_id');

        return $this->fetchAll($select);
    }

    public function getListing() {

        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('states', 'states.state_id = mla.state', array('state_name' => 'state'));
        $select->joinLeft('roles', 'roles.role_id = mla.role_id', array('role_name'));
                $select->joinLeft('mlo', 'mlo.mla_id = mla.mla_id', array('mlo_name','mlo_id', 'mlo_state_name' => new Zend_Db_Expr("(Select states.state from states where states.state_id = mlo.state)"), 'mlo_lga_name' => new Zend_Db_Expr("(Select lgas.name from lgas where lgas.id = mlo.lga)")));
        $select->joinLeft('lgas', 'mla.lga = lgas.id', array('name'));
        $select->order('mla_id');
        return $select;
    }

    public function getMloByEmail($email) {
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('roles', ' roles.role_id = mlo.role_id', array("role_name"))
                ->where('username = ?', $email);
        //  die($select);
        return $this->fetchRow($select);
    }

    public function getMlas() {
        $select = $this->select();
        return $this->fetchAll($select);
    }

    public function deleteMlas(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteMlaById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>