<?php

class My_View_Helper_DataTable extends Zend_View_Helper_Abstract{
	protected $_selection=false;
	
	protected $_fields;
	
	protected $_columns;
	
	protected $_model;
	
	protected $_callbacks = array();
	
	protected $_attribs;
	
	public function dataTable($columns, $model, $attribs=array(), $selection=false){
    	$this->_fields = array_keys($columns);
    	$this->_columns = $columns;
    	
    	if(!is_array($model)){
    		if($model instanceof Traversable){
    			$model = $model->toArray();
    		}
    	}
    	
    	$this->_model = $model;
    	$this->_attribs = $attribs;
		$this->_selection = $selection;
		
		return $this;
	}
	
	public function openTag()
	{
		$tag = '<table';
		 
		foreach ($this->_attribs as $attrib => $value){
			$tag .=  ' ' . $attrib . '="' . $value . '"';
		}
		 
		$tag .= '>';
		
		 
		return $tag;
	}
	
    public function endTag()
    {
    	$tag = '</table>';
    	
    	
    	if($this->_selection){
    		$this->view->headScript()->appendFile('/static/jquery/js/jquery-1.7.2.min.js')
    			->appendFile('/themes/2/js/jquery.selectable.js')
    			->appendScript('$(function(){ $(".selectable.master").selection(); })');
    	}
    	
        return $tag;
    }
    
    public function tableHead(){
    	
    	$markup = '<thead><tr>';
    	
    	if($this->_selection){
    		$markup .= '<th width="1">';
    		$markup .= $this->view->formCheckbox('master', null, array('class'=>'selectable master'));
    		$markup .= '</th>';
    	}
    	
    	foreach ($this->_columns as $field => $column) {
    		
    		$markup .= '<th';
    		$title = null;
    		
    		if(is_array($column)){
    			$title = array_key_exists('title', $column) ? $column['title'] : $title;
    			
    			if(array_key_exists('callback', $column)){
    				if(is_callable($column['callback'])){
    					$this->_callbacks[$field] = $column['callback'];
    				}
    			}
    			
    			if(array_key_exists('attribs', $column)){
					foreach ($column['attribs'] as $attrib => $value){
						$markup .=  ' ' . $attrib . '="' . $value . '"';
					}
    			}
    			
    		}elseif(is_string($column)){
    			$title = $column;
    		}
    		
    		$markup .= '>';
    		$markup .= $title;
    		$markup .= '</th>';
    	}
    	
    	$markup .= '</tr></thead>';
    	 
    	
    	return $markup;
    }
    
    public function tableBody(){
    	$markup = '<tbody>';
    	
    	if($this->_model){
	    	foreach ($this->_model as $row) {
		    	$markup .= '<tr>';
		    	
		    	if($this->_selection){
		    		$markup .= '<td width="1">';
		    		$markup .= $this->view->formCheckbox('selection', $row[$this->_selection['identity']], array('class'=>'selectable slave'));
		    		$markup .= '</td>';
		    	}
		    	
	    		foreach ($this->_fields as $field){
	    			$value = null;
	    			
	    			if(array_key_exists($field, $row)){
	    				$value = $row[$field];
	    			}
	    			
	    			if(array_key_exists($field, $this->_callbacks)){
	    				$value = $this->_callbacks[$field]($value, $row, $this->view);
	    			}
	    			
		    		$markup .= '<td>';
		    		$markup .= $value;
		    		$markup .= '</td>';
	    		}
	    		
	    		$markup .= '</tr>';
	    	}
	    	
	    	$markup .= '</tbody>';
	    	
	    	return $markup;
    	} else {
    		
    	}
    }
    
    
    public function render(){
    	$markup = $this->openTag();
    	$markup .= $this->tableHead();
    	$markup .= $this->tableBody();
    	$markup .= $this->endTag();
    	
    	return $markup;
    }
    
    public function __toString(){
    	return $this->render();
    }
	
}
