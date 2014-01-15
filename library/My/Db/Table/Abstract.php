<?php

require_once ('Zend/Db/Table/Abstract.php');

class My_Db_Table_Abstract extends Zend_Db_Table_Abstract {
    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = 'My_Db_Table_Rowset';
    
 	public function select($withFromPart = parent::SELECT_WITHOUT_FROM_PART)
    {
        require_once 'My/Db/Table/Select.php';
        $select = new My_Db_Table_Select($this);
        if ($withFromPart == self::SELECT_WITH_FROM_PART) {
            $select->from($this->info(self::NAME), Zend_Db_Table_Select::SQL_WILDCARD, $this->info(self::SCHEMA));
        }
        return $select;
    }
}