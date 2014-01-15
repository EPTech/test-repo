<?php


class Default_Resource_Countries extends PP_Model_Resource_Db_Table_Abstract {

    protected $_name = 'countries';
    protected $_primary = 'country_id';
  
    public function getCountryById($id) {
        return $this->find($id)->current();
    }

    public function getCountries(){
        $select = $this->select();
        return $this->fetchAll($select);
    }
   
  
    public function deleteCountries(array $ids) {
        foreach ($ids as $val) {
            $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $val);
            $this->delete($where);
        }
        return true;
  }

    public function deleteCountryById($id) {
        $where = $this->getAdapter()->quoteInto($this->_primary . ' = ?', $id);
        return $this->delete($where);
    }

}

?>