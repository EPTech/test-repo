<?php

class My_View_Helper_HtmlTable extends Zend_View_Helper_Abstract {

    protected $_selection = false;
    protected $_fields;
    protected $_columns;
    protected $_model;
    protected $_callbacks = array();
    protected $_attribs;
    protected $_columnAttribs = array();
    protected $_options;

    public function htmlTable($columns, $model, $options = array()) {
        $this->_fields = array_keys($columns);
        $this->setColumns($columns);

        if (!is_array($model)) {
            if ($model instanceof Traversable) {
                $model = $model->toArray();
            }
        }

        $this->_model = $model;
        
        $this->setOptions($options);

        return $this;
    }
    
    /**
     * Set form state from options array
     *
     * @param  array $options
     * @return Zend_Form
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $normalized = ucfirst($key);
            
            $method = 'set' . $normalized;
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                $this->setAttrib($key, $value);
            }
        }
        
        return $this;
    }
    
    public function setAttrib($key, $value){
        $this->_attribs[$key] = $value;
    }
    
    public function setSelection($config){
        $this->_selection = $config;
    }

    public function openTag() {
        $tag = '<table';

		if(!empty($this->_attribs)){
			foreach ($this->_attribs as $attrib => $value) {
				$tag .= ' ' . $attrib . '="' . $value . '"';
			}
		}

        $tag .= '>';


        return $tag;
    }

    public function endTag() {
        $tag = '</table>';


        if ($this->_selection) {
            $this->view->headScript()->appendFile('/static/jquery/js/jquery-1.7.2.min.js')
                    ->appendFile('/themes/2/js/jquery.selectable.js')
                    ->appendScript('$(function(){ $(".selectable.master").selection(); })');
        }

        return $tag;
    }

    public function tableHead() {

        $markup = '<thead><tr>';

        if ($this->_selection) {
            $markup .= '<th width="1">';
            $markup .= $this->view->formCheckbox('master', null, array('class' => 'selectable master'));
            $markup .= '</th>';
        }

        foreach ($this->_columns as $field => $column) {

            $markup .= '<th';
            if(isset($this->_column[$field]['attribs']))
            foreach ($this->_column[$field]['attribs'] as $attrib => $value) {
                $markup .= ' ' . $attrib . '="' . $value . '"';
            }

            $markup .= '>';
            $markup .= $column['title'];
            $markup .= '</th>';
        }

        $markup .= '</tr></thead>';


        return $markup;
    }

    public function setColumns($specs) {
        $title = null;
        foreach ($specs as $field => $column) {
            if (is_array($column)) {
                $this->_columns[$field]['title'] = array_key_exists('title', $column) ? $column['title'] : $title;

                if (array_key_exists('callback', $column)) {
                    if (is_callable($column['callback'])) {
                        $this->_callbacks[$field] = $column['callback'];
                    }
                }

                if (array_key_exists('attribs', $column)) {
                    $this->_column[$field]['attribs'] = $column['attribs'];
                }
            } elseif (is_string($column)) {
                $this->_columns[$field]['title'] = $column;
            }
        }
    }

    public function tableBodyHtml() {
        $markup = '<tbody>';

        if ($this->_model) {
            foreach ($this->_model as $index => $row) {
                $markup .= '<tr>';

                if ($this->_selection) {
                    $markup .= '<td width="1">';
                    $markup .= $this->view->formCheckbox('selection', $row[$this->_selection['identity']], array('class' => 'selectable slave'));
                    $markup .= '</td>';
                }

                foreach ($this->_fields as $field) {
                    $value = null;

                    if (array_key_exists($field, $row)) {
                        $value = $row[$field];
                    }

                    if (array_key_exists($field, $this->_callbacks)) {
                        $value = $this->_callbacks[$field]($value, $row, $index, $this->view);
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

    public function jsonData() {
        $markup = '[';

        if ($this->_model) {
            $comma1 = null;
            foreach ($this->_model as $index => $row) {
                $markup .= $comma1 . '[';

                if ($this->_selection) {
                    $markup .= '"';
                    $markup .= $this->view->formCheckbox('selection', $row[$this->_selection['identity']], array('class' => 'selectable slave'));
                    $markup .= '"';
                    
                    if(count($this->_fields)){
                        $markup .= ',';
                    }
                }
                $comma2 = null;
                foreach ($this->_fields as $field) {
                    $value = null;

                    if (array_key_exists($field, $row)) {
                        $value = $row[$field];
                    }

                    if (array_key_exists($field, $this->_callbacks)) {
                        $value = $this->_callbacks[$field]($value, $row, $index, $this->view);
                    }

                    $markup .= $comma2;
                    $markup .= '"';
                    $markup .= addslashes($value);
                    $markup .= '"';
                    
                    $comma2 = ',';
                }

                $markup .= ']';
                $comma1 = ',';
            }
        } else {
            
        }

        $markup .= ']';

        return $markup;
    }

    public function render() {
        $markup  = $this->openTag();
        $markup .= $this->tableHead();
        $markup .= $this->tableBodyHtml();
        $markup .= $this->endTag();

        return $markup;
    }

    public function __toString() {
        return $this->render();
    }

}
