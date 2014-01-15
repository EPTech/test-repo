<?php

class My_View_Helper_LiteralizeFK extends Zend_View_Helper_Abstract{
    public function literalizeFK($key, $keyCol, $dbTable, $literalCol)
    {
    	$table = new Zend_Db_Table($dbTable);
    	$select = $table->select()->from($table, array($literalCol))->where($keyCol .' = ?', $key);
    	
    	return $table->fetchRow($select)->{$literalCol};
    	
    }
	
}
