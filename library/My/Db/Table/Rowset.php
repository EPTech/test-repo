<?php
class My_Db_Table_Rowset extends Zend_Db_Table_Rowset{
	
	public function foundRows(){
		return $this->_table->getAdapter()->fetchOne('SELECT FOUND_ROWS()');
	}
}