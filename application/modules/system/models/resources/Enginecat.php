<?php


class System_Resource_Enginecat extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'engine_categories';
    protected $_primary = 'engcat_id';
  
    public function getEnginecatById($id) {
        return $this->find($id)->current();
    }

    public function getEnginecategories(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
  
    public function deleteEnginecategories(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteEnginecategoryById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>