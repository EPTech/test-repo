<?php
class System_Resource_Adminpage2profile_Item extends PP_Model_Resource_Db_Table_Row_Abstract implements Zend_Acl_Role_Interface {

    public function getFullname() {
        return $this->getRow()->fullname;
    }

    public function getRoleId() {
        if (null === $this->getRow()->role_id) {
            return 'Guest';
        }
        return $this->getRow()->role_id;
    }

}
