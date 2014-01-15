<?php

require_once ('Zend/Db/Table/Select.php');

class My_Db_Table_Select extends Zend_Db_Table_Select {
	const CALC_FOUND_ROWS = 'calcfoundrows';
	const SQL_CALC_FOUND_ROWS = 'SQL_CALC_FOUND_ROWS';
	
    /**
     * The initial values for the $_parts array.
     * NOTE: It is important for the 'FOR_UPDATE' part to be last to ensure
     * meximum compatibility with database adapters.
     *
     * @var array
     */
    protected static $_partsInit = array(
        self::CALC_FOUND_ROWS	=> false,
        self::DISTINCT     	=> false,
        self::COLUMNS      	=> array(),
        self::UNION        	=> array(),
        self::FROM         	=> array(),
        self::WHERE        	=> array(),
        self::GROUP        	=> array(),
        self::HAVING       	=> array(),
        self::ORDER        	=> array(),
        self::LIMIT_COUNT  	=> null,
        self::LIMIT_OFFSET 	=> null,
        self::FOR_UPDATE   	=> false
    );


    /**
     * Converts this object to an SQL SELECT string.
     *
     * @return string|null This object as a SELECT string. (or null if a string cannot be produced.)
     */
//    public function assemble()
//    {
//        $sql = self::SQL_SELECT;
//        foreach (array_keys(self::$_partsInit) as $part) {
//            $method = '_render' . ucfirst($part);
//            if (method_exists($this, $method)) {
//                $sql = $this->$method($sql);
//            }
//            echo $sql . "<br/>";
//        }
//        die;
//        return $sql;
//    }
    
	protected function _renderCalcFoundRows($sql){
		
        if (isset($this->_parts[self::CALC_FOUND_ROWS]) && $this->_parts[self::CALC_FOUND_ROWS]) {
            $sql .= ' ' . self::SQL_CALC_FOUND_ROWS;
        }

        return $sql;
	}
	
    public function calcFoundRows($flag = true)
    {
        $this->_parts[self::CALC_FOUND_ROWS] = (bool) $flag;
        //Zend_Debug::dump($this->_parts);
        return $this;
    }
}
