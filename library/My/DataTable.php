<?php

class My_DataTable {
    
	protected $_ajaxSource = null;
	
	protected $_defaultDisplayLength = '20';
	
	protected $_dataTableOptions = array();
	
	protected $_ufCount;
	
	protected $_fCount;
	
	protected $_select;
	
	protected $_origSelect;
	
	protected $_searchColumns;
	
	protected $_options;
	
	protected $_data;
	
	public function __construct(Zend_Db_Table_Select $select, array $searchColumns = null, $options = array()){
		$this->_select = $select;
		
		$this->_origSelect = clone $select;
		
		$this->_options = $options;
		
		if(!is_null($searchColumns))
			$this->setColumns($searchColumns);
			
			
		$this->_options['iDisplayStart'] = isset($this->_options['iDisplayStart']) ? 
			$this->_options['iDisplayStart'] : '0';
			
		$this->_options['iDisplayLength'] = isset($this->_options['iDisplayLength']) ? 
			$this->_options['iDisplayLength'] : $this->getDefaultDisplayLength();
			
		$this->filter();
             
		$this->order();
		$this->limit();
	}
	
	public function setDefaultDisplayLength($value){
		$this->_defaultDisplayLength = $value;
		return $this;
	}
	
	public function getDefaultDisplayLength(){
		return $this->_defaultDisplayLength;
	}
	
	public function getColumns(){
		return $this->_searchColumns;
	}
	
	public function setColumns(array $cols){
		$this->_searchColumns = $cols;
		return $this;
	}
	
    public function fetchAll()
    {
        return $this->getData();
    }
	
	public function getData(){
		if(!$this->_data instanceof Zend_Db_Table_Rowset_Abstract){
			
			$this->_data = $this->_select->getTable()->fetchAll($this->_select);
		}		
		
		return $this->_data;
	}
	
	public function getFilteredTotal(){
		///check if count has been cached
           
		if(is_null($this->_fCount)){
	        $cSelect = clone $this->_select;
			 
	        $cSelect->reset(Zend_Db_Table_Select::LIMIT_COUNT)
	        	->reset(Zend_Db_Table_Select::LIMIT_OFFSET);
			//die("ojima");	
			$fSelect= "SELECT COUNT(1) FROM ($cSelect) fSelect";
			
		    
			$this->_fCount = $cSelect->getAdapter()->fetchOne($fSelect);
					
		}
		
		return $this->_fCount;
	}
	
	public function getTotal(){
		if(is_null($this->_ufCount)){			
			$ufSelect= "SELECT COUNT(1) FROM ($this->_origSelect) ufSelect";
			
			$this->_ufCount = $this->_origSelect->getAdapter()->fetchOne($ufSelect);
		}
		
		return $this->_ufCount;
	}
	
	public function filter(){
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		if ( isset($this->_options['sSearch']) && $this->_options['sSearch'] != "" )
		{
			$append = count($this->_select->getPart(Zend_Db_Table_Select::WHERE)) > 0;
			
			$bracket = '(';
					
			for ( $i=0 ; $i<count($this->_searchColumns)-1 ; $i++ )
			{
                            
				if ( isset($this->_options['bSearchable_'.$i]) && $this->_options['bSearchable_'.$i] == "true" )
				{
                                    	if($append){
						$this->_select->where("{$bracket}{$this->_searchColumns[$i]} LIKE ? ", "%{$this->_options['sSearch']}%");
						$append = false;
					}else{
						$this->_select->orWhere("{$bracket}{$this->_searchColumns[$i]} LIKE ? ", "%{$this->_options['sSearch']}%");
					}
				}
				
				$bracket = '';
			}
			
			$this->_select->orWhere("{$this->_searchColumns[$i]} LIKE ?)", "%{$this->_options['sSearch']}%");
		}
	
		/**
		 *  Individual column filtering 
		 *  
		 */
		for ( $i=0 ; $i<count($this->_searchColumns) ; $i++ )
		{
			if ( isset($this->_options['bSearchable_'.$i]) && $this->_options['bSearchable_'.$i] == "true" && $this->_options['sSearch_'.$i] != '' )
			{
				$this->_select->where("{$this->_searchColumns[$i]} LIKE ?", "%{$this->_options['sSearch_'.$i]}%");
			}
		}
		
              //  die($this->_select);
    }
    
	public function limit(){
    	//Paging
    	if ( isset( $this->_options['iDisplayStart'] ) && $this->_options['iDisplayLength'] != '-1')
		{	
	    	$this->_select->limit($this->_options['iDisplayLength'], $this->_options['iDisplayStart']);
		}
		
	}
	
	public function order(){
		//Ordering
		if ( isset( $this->_options['iSortCol_0'] ) && $this->_options['iSortCol_0'] != '' )
		{
			//remove default ordering
			$this->_select->reset(Zend_Db_Table_Select::ORDER);
		
			for ( $i=0 ; $i<intval( $this->_options['iSortingCols'] ) ; $i++ )
			{
				if ( $this->_options[ 'bSortable_'.intval($this->_options['iSortCol_'.$i]) ] == "true" )
				{
					$oColumn = $this->_searchColumns[ intval( $this->_options['iSortCol_'.$i] ) ];
					$oDir = $this->_options['sSortDir_'.$i];
					$this->_select->order($oColumn . ' ' . $oDir);
				}
			}
		}
	}
	
	public function render(){
		$helper = new My_View_Helper_HtmlTable();
		$html = $helper->htmlTable($this->_searchColumns, $this->fetchAll());
		return $html->render();
	}
	
	public function renderJSON(){
		$helper = new My_View_Helper_HtmlTable();
		$html = $helper->htmlTable($this->_searchColumns, $this->fetchAll());
		return $html->jsonData();
	}
	
	public function getMeta(){
		
	}
}