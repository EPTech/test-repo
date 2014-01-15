<?php
class System_Model_DbTable_Resources extends Zend_Db_Table_Abstract
{
	protected $_name = "resources";
	
	protected $_rowClass = "System_Model_Resource";
}