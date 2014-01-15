<?php

//require_once dirname(__FILE__) . '/States/Item.php';

class System_Resource_Staff extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'staff';
    protected $_primary = 'staff_id';
   

    public function getStaffById($id) {
        //return $this->find($id)->current();
        $select = $this->select(true)->setIntegrityCheck(false);
        $select->joinLeft('roles', 'staff.role_id = roles.role_id', array('role_name'));
        $select->joinLeft('states', 'states.state_id = staff.state', array('state'));
        $select->where("staff_id = ?", $id);
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
        $select->joinLeft('roles', 'staff.role_id = roles.role_id', array('role_name'));
        $select->joinLeft('states', 'states.state_id = staff.state', array('state_name' => 'state'));
        $select->joinLeft('lgas', 'lgas.id = staff.lga', array('name'));
        $select->order('staff_id');
        return $select;
    }
    public function getStaff(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
    
    public function getStaffByEmail($email){
        $select = $this->select();
        $select->where("staff_username = ?", $email);
        return $this->fetchRow($select);
    }
   
    public function deleteStaff(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteStaffById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>