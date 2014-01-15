<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 *
 * @author TUNDE
 */
abstract class My_Model_Mapper_Abstract {
	/**
	 * DbTable of model
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_table;
	
	public abstract function getTable();
	
	public function find($id){
		$table = $this->getTable();
		return $table->find($id);
	}
	
	public function fetchAll(){
		$table = $this->getTable();
		
		return $table->fetchAll();
	}
	
	public function save(Zend_Db_Table_Row_Abstract $model){
		$model->save();
	}
}

