<?php

//require_once dirname(__FILE__) . '/States/Item.php';

class System_Resource_Titles extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'titles';
    protected $_primary = 'title_id';
   

    public function getTitleById($id) {
        return $this->find($id)->current();
    }

    public function getTitles(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
    public function deleteTitle(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteTitleById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>