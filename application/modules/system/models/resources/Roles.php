<?php

//require_once dirname(__FILE__) . '/Lgas/Item.php';

class System_Resource_Roles extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'roles';
    protected $_primary = 'role_id';

    public function getRoleById($id) {
        return $this->find($id)->current();
    }

    public function getLastRole(){
        $select = $this->select();
        $select->from("roles",array('role_id' => new Zend_Db_Expr("MAX(role_id)")));
        return $this->fetchRow($select);
    }
    public function getRoles() {
        $select = $this->select();
        return $this->fetchAll($select);
    }

    public function deleteRoles(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteRoleById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>