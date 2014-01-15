<?php
class My_Form_Element_Duration extends Zend_Form_Element
{
	protected $_unitValue;
	protected $_measureValue;
	
	protected $_measureOptions;
	
	public function __construct($spec, $options=null){
		parent::__construct($spec, $options);
	}
	
	public function loadDefaultDecorators() {
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return;
		}
	
		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this->addDecorator('Duration')
			->addDecorator('Errors')
			->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
			->addDecorator('HtmlTag', array(
					'tag' => 'dd',
					'id' => $this->getFullyQualifiedName() . '-element'
			))
			->addDecorator('Label', array('tag' => 'dt'));
		}
	}
	
	public function setValue(array $value){
		$this->_unitValue = $value['unit'];
		$this->_measureValue = $value['measure'];
		
		return $this;
	}
	
	/**
     * Retrieve options array
     *
     * @return array
     */
    protected function _getMeasureMultiOptions()
    {
        if (null === $this->_measureOptions || !is_array($this->_measureOptions)) {
            $this->_measureOptions = array();
        }

        return $this->_measureOptions;
    }

    /**
     * Add an option
     *
     * @param  string $option
     * @param  string $value
     * @return Zend_Form_Element_Multi
     */
    public function addMeasureMultiOption($option, $value = '')
    {
        $option  = (string) $option;
        $this->_getMeasureMultiOptions();
//         if (!$this->_translateOption($option, $value)) {
//             $this->_measureOptions[$option] = $value;
//         }

        return $this;
    }

    /**
     * Add many options at once
     *
     * @param  array $options
     * @return Zend_Form_Element_Multi
     */
    public function addMultiOptions(array $options)
    {
        foreach ($options as $option => $value) {
            if (is_array($value)
                && array_key_exists('key', $value)
                && array_key_exists('value', $value)
            ) {
                $this->addMeasureMultiOption($value['key'], $value['value']);
            } else {
                $this->addMeasureMultiOption($option, $value);
            }
        }
        return $this;
    }

    /**
     * Set all options at once (overwrites)
     *
     * @param  array $options
     * @return Zend_Form_Element_Multi
     */
    public function setMeasureMultiOptions(array $options)
    {
        $this->clearMeasureMultiOptions();
        return $this->addMeasureMultiOptions($options);
    }

    /**
     * Retrieve single multi option
     *
     * @param  string $option
     * @return mixed
     */
    public function getMeasureMultiOption($option)
    {
        $option  = (string) $option;
        $this->_getMeasureMultiOptions();
        if (isset($this->_measureOptions[$option])) {
            //$this->_translateOption($option, $this->_measureOptions[$option]);
            return $this->_measureOptions[$option];
        }

        return null;
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    public function getMeasureMultiOptions()
    {
        $this->_getMultiOptions();
//         foreach ($this->_measureOptions as $option => $value) {
//             $this->_translateOption($option, $value);
//         }
        return $this->_measureOptions;
    }

    /**
     * Remove a single multi option
     *
     * @param  string $option
     * @return bool
     */
    public function removeMeasureMultiOption($option)
    {
        $option  = (string) $option;
        $this->_getMultiOptions();
        if (isset($this->_measureOptions[$option])) {
            unset($this->_measureOptions[$option]);
//             if (isset($this->_translated[$option])) {
//                 unset($this->_translated[$option]);
//             }
            return true;
        }

        return false;
    }

    /**
     * Clear all options
     *
     * @return Zend_Form_Element_Multi
     */
    public function clearMeasureMultiOptions()
    {
        $this->_measureOptions = array();
        $this->_translated = array();
        return $this;
    }
}
