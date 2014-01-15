<?php

/**
 * SF_Model_Resource_Db_Table_Abstract
 * 
 * Provides some common db functionality that is shared
 * across our db-based resources.
 * 
 */
abstract class PP_Model_Resource_Db_Table_Abstract extends Zend_Db_Table_Abstract implements PP_Model_Resource_Db_Interface {

    // protected $_primary = $this->info('primary');
    /**
     * Save a row to the database
     *
     * @param array             $info The data to insert/update
     * @param Zend_DB_Table_Row $row Optional The row to use
     * @return mixed The primary key
     */
    public function saveRow($info, $row = null) {
        if (null === $row) {
            // exit("create row");
            $row = $this->createRow();
        }

        $columns = $this->info('cols');
        foreach ($columns as $column) {
            if (array_key_exists($column, $info)) {
                $row->$column = $info[$column];
            }
        }
        //exit("continue");
        return $row->save();
    }

    public function getAllColumns() {
        $info = $this->info('cols');
        return $info;
    }

    public function getListing() {
        $select = $this->select();

        return $select;
    }

    public function getPrimaryKey() {
        $pk = $this->info(Zend_Db_Table_Abstract::PRIMARY);
        if (count($pk) === 1) {
            $pk = implode('', $pk);
        }

        return $pk;
    }

    public function saveItem($data) {
        return $this->insert($data);
    }

    public function updateItem($data) {
        $where = $this->getDefaultAdapter()->quoteInto($this->getPrimaryKey() . " = ?", $data[$this->getPrimaryKey()]);
        return $this->update($data, $where);
    }

    public function getItemById($id) {
        $select = $this->select()->where($this->getPrimaryKey() . " = ?", $id);
        return $this->fetchRow($select);
    }

    public function deleteItems(array $ids) {
        foreach ($ids as $val) {
            if($val == ""){
                continue;
            }
            $where = $this->getAdapter()->quoteInto($this->getPrimaryKey() . ' = ?', $val);
            $this->delete($where);
        }
        return true;
    }

    public function deleteItemById($id) {
        $where = $this->getAdapter()->quoteInto($this->getPrimaryKey() . ' = ?', $id);
        return $this->delete($where);
    }

}
