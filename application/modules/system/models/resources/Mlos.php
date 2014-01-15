<?php

//require_once dirname(__FILE__) . '/States/Item.php';

class System_Resource_Mlos extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'mlo';
    protected $_primary = 'mlo_id';

    public function getMloById($id) {
        //return $this->find($id)->current();
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('roles', 'mlo.role_id = roles.role_id', array('role_name'));
           $select->joinLeft('states', 'states.state_id = mlo.state', array('state_name' => 'state'));
         $select->joinLeft('lgas', 'mlo.lga = lgas.id', array('name'));
        $select->where("mlo_id = ?", $id);
        $select->order('mlo_id');
        //die($select);
        return $this->fetchRow($select);
    }

    public function createSalt() {
        $salt = "";
        for ($i = 0; $i < 50; $i++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }

    public function getListing() {

        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('states', 'states.state_id = mlo.state', array('state_name' => 'state'));
        $select->joinLeft('lgas', 'mlo.lga = lgas.id', array('name'));
        $select->order('mlo_id');
        return $select;
    }

     public function getMloByEmail($email) {
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('roles', ' roles.role_id = mlo.role_id', array("role_name"))
                ->joinLeft("mla", "mla.mla_id = mlo.mla_id", array("mla_firstname", "mla_lastname"))
                ->where('username = ?', $email);
        //  die($select);
        return $this->fetchRow($select);
    }

    
    public function getMlos() {
        $select = $this->select();
        return $this->fetchAll($select);
    }

    public function deleteMlos(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteMloById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>