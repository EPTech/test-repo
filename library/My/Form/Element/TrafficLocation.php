<?php
class My_Form_Element_TrafficLocation extends Zend_Form_Element_Xhtml{
	protected $_country_id;
	protected $_state_id;
	protected $_city_id;
	
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'My_Form_Decorator',
            'My/Form/Decorator',
            'decorator'
        );
        parent::__construct($spec, $options);
    }
    
    public function getCountryOptions($country_id){
    	$table = new Zend_Db_Table('countries');
    					
    	return $table->fetchAll()->toArray();
    }
    
    /**
	 * @return the $_country_id
	 */
	public function getCountry() {
		return $this->_country_id;
	}

	/**
	 * @return the $_state_id
	 */
	public function getState() {
		return $this->_state_id;
	}

	/**
	 * @return the $_city_id
	 */
	public function getCity() {
		return $this->_city_id;
	}

	/**
	 * @param field_type $_country_id
	 */
	public function setCountry($_country_id) {
		$this->_country_id = $_country_id;
    	return $this;
	}

	/**
	 * @param field_type $_state_id
	 */
	public function setState($_state_id) {
		$this->_state_id = $_state_id;
    	return $this;
	}

	/**
	 * @param field_type $_city_id
	 */
	public function setCity($_city_id) {
		$this->_city_id = $_city_id;
    	return $this;
	}
	
	public function setValue($value){
		parent::setValue($value);
		
    	$table = new Zend_Db_Table('traffic_locations');
    	$select = $table->select()->setIntegrityCheck(false)
    			->from($table)
    			->joinLeft('traffic_roads',	'traffic_locations.road_id = traffic_roads.id')
    			->where('traffic_locations.id = ?', $value);
    	
    	$location = $table->fetchRow($select);
    	
    	$this->setCountry($location->country_id)
	    	->setState($location->state_id)
	    	->setCity($location->city_id);
    	
    	return $this;
	}

	public function getStateOptions(){
    	$table = new Zend_Db_Table('states');
    	
    	if(!is_null($this->_country_id))
    		$select = $table->select()
    					->where('country_id = ?', $this->_country_id);

    	foreach ($table->fetchAll($select) as $country){
    		$selection[$country->id] = $country->name;
    	}
    	
    	return $selection;
    }
    
    public function getCityOptions(){
    	$table = new Zend_Db_Table('cities');
    	
    	if(!is_null($this->_country_id))
    		$select = $table->select()
    					->where('country_id = ?', $this->_country_id);
    					
    	if(!is_null($this->_state_id))
    		$select = $select->where('state_id = ?', $this->_state_id);
    		

    	foreach ($table->fetchAll($select) as $city){
    		$selection[$city->id] = $city->name;
    	}
    	
    	return $selection;
    }
    
    public function getLocationOptions(){
    	$table = new Zend_Db_Table('traffic_locations');
    	
    	if(!is_null($this->_country_id))
    		$select = $table->select()
    					->where('country_id = ?', $this->_country_id);
    					
    	if(!is_null($this->_state_id))
    		$select = $select->where('state_id = ?', $this->_state_id);
    					
    	if(!is_null($this->_city_id))
    		$select = $select->where('city_id = ?', $this->_city_id);
    	
    	foreach ($table->fetchAll($select) as $location){
    		$selection[$location->id] = $location->road;
    	}
    	
    	return $selection;
    }
    
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('TrafficLocation')
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array(
                     'tag'   => 'p',
                     'class' => 'description'
                 ))
                 ->addDecorator('HtmlTag', array(
                     'tag' => 'dd',
                     'id'  => $this->getName() . '-element'
                 ))
                 ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
}