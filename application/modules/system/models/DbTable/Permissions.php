<?php
class System_Model_DbTable_Permissions extends Zend_Db_Table_Abstract
{
	protected $_name = "permissions";
	
	public function grant($role, $resource){
		return $this->insert(array(
			'role_id' => $role,
			'resource_id' => $resource
		));
	}
	
	public function deny($role, $resource){
		$adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
		
		$where = $adapter->quoteInto('role_id = ?', $role);
		
		$where = $adapter->quoteInto($where . ' AND resource_id = ?', $resource);
		
		return $this->delete($where);
	}
}