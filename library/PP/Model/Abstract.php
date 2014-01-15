<?php
/**
 * SF_Model_Abstract
 * 
 * Base model class that all our models will inherit from.
 * 
 * The main purpose of the base model is to provide models with a way
 * of handling their resources.
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class PP_Model_Abstract implements PP_Model_Interface 
{
    /**
    * @var array Class methods
    */
    protected $_classMethods;
    
    /**
     * @var array Model resource instances
     */
    protected $_resources = array();

    /**
     * @var array Form instances
     */
    protected $_forms = array();

   /**
    * Constructor
    *
    * @param array|Zend_Config|null $options
    * @return void
    */
    public function __construct($options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (is_array($options)) {
            $this->setOptions($options);
        }

        $this->init();
    }

    /**
     * Constructor extensions
     */
    public function init()
    {}

   /**
    * Set options using setter methods
    *
    * @param array $options
    * @return SF_Model_Abstract 
    */
    public function setOptions(array $options)
    {
        if (null === $this->_classMethods) {
            $this->_classMethods = get_class_methods($this);
        }
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $this->_classMethods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

	/**
	 * Get a resource
	 *
	 * @param string $name
	 * @return SF_Model_Resource_Interface 
	 */
	public function getResource($name) 
	{
        if (!isset($this->_resources[$name])) {
            $class = join('_', array(
                    $this->_getNamespace(),
                    'Resource',
                    $this->_getInflected($name)
            ));
            $this->_resources[$name] = new $class();
        }
	    return $this->_resources[$name];
	}

        
     
    
    
    
     public function updateLockedTableRows(array $info, $tablePrimarykey, $tableResourceClass) {
        $data = $info;
        
      $cols = $this->getResource($tableResourceClass)->getAllColumns();

            // $data array contains keys that are not columns in the table hence the need for new array
            $newData = array();

            foreach ($cols as $col) {

                //if  exist in table 
                if (key_exists($col, $data)) {
                    $newData[$col] = $data[$col];
                }
            }
            
            
        $where = $this->getResource($tableResourceClass)->getAdapter()->quoteInto($tablePrimarykey . ' = ?',  $newData[$tablePrimarykey]);
        return $this->getResource($tableResourceClass)->update( $newData , $where);
    }
    
        public function createTableRow(array $info, $tableResourceClass) {
        
            $data = $info;
        
            $cols = $this->getResource($tableResourceClass)->getAllColumns();

            // $data array contains keys that are not columns in the table hence the need for new array
            $newData = array();

            foreach ($cols as $col) {

                //if  exist in table 
                if (key_exists($col, $data)) {
                    $newData[$col] = $data[$col];
                }
            }
            
        return $this->getResource($tableResourceClass)->saveRow($newData);   
    }

    /**
     * Get a Form
     * 
     * @param string $name
     * @return Zend_Form 
     */
    public function getForm($name)
    {
        if (!isset($this->_forms[$name])) {
            $class = join('_', array(
                    $this->_getNamespace(),
                    'Form',
                    $this->_getInflected($name)
            ));
            $this->_forms[$name] = new $class();
        }
	    return $this->_forms[$name];
    }

    /**
     * Classes are named spaced using their module name
     * this returns that module name or the first class name segment.
     * 
     * @return string This class namespace 
     */
    private function _getNamespace()
    {
        $ns = explode('_', get_class($this));
        return $ns[0];
    }

    /**
     * Inflect the name using the inflector filter
     * 
     * Changes camelCaseWord to Camel_Case_Word
     * 
     * @param string $name The name to inflect
     * @return string The inflected string 
     */
    private function _getInflected($name)
    {
        $inflector = new Zend_Filter_Inflector(':class');
        $inflector->setRules(array(
            ':class'  => array('Word_CamelCaseToUnderscore')
        ));
        return ucfirst($inflector->filter(array('class' => $name)));
    }
}