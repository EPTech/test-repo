<?php

class My_Form_Element_Db_Select extends Zend_Form_Element_Select {

    protected $_table = null;
    protected $_labelField = null;
    protected $_valueField = null;
    protected $_constraint = null;

    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);

//        $this->addPrefixPath(
//            'My_Form_Decorator',
//            'My/Form/Decorator',
//            'decorator'
//        );
        
        $this->populate();
    }

    public function setBinding($options) {
        if (!is_array($options)) {
            require_once 'Zend/Form/Exception.php';
            throw new Zend_Form_Element_Exception('Invalid argument provided.');
        } else {
            $this->setBindingOptions($options);
        }

        return $this;
    }

    public function setTable($table) {
        if ($table instanceof Zend_Db_Table_Abstract) {
            $this->_table = $table;
        } else if (is_string($table)) {
            $dbTableClass = $table;

            if (class_exists($dbTableClass)) {
                $dbTable = new $dbTableClass;

                if ($dbTable instanceof Zend_Db_Table_Abstract) {
                    $this->_table = $dbTable;
                }
            } else {
                $this->_table = new Zend_Db_Table($table);
            }
        }
        //Zend_Debug::dump($this->_dbTable);
        return $this;
    }

    public function setValueField($value) {
        $this->_valueField = $value;
    }
    
    public function setConstraint($value) {
        $this->_constraint = $value;
    }

    public function setLabelField($value) {
        $this->_labelField = $value;
    }

    public function getDbTable() {
        return $this->_dbTable;
    }

    private function setBindingOptions($options) {
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                // Setter exists; use it
                $this->$method($value);
            }
        }

        return $this;
    }

    private function populate() {
        if (!is_null($this->_table)) {
        	$select = $this->_table->select();
        	$select->from($this->_table, array('value' => $this->_valueField, 'label' => $this->_labelField));
        	
        	if(!empty($this->_constraint)){
        		$select->where($this->_constraint[0], $this->_constraint[1]);
        	}
        	
            $options = $this->_table->fetchAll($select);
            

            foreach ($options as $option) {
                $this->addMultiOption($option->value, $option->label);
            }
        }
    }

}