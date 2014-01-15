<?php
class My_Form_Decorator_TrafficLocation extends Zend_Form_Decorator_ViewHelper{
	public function render($content)
    {
    	$element = $this->getElement();    	
    	if (!$element instanceof My_Form_Element_TrafficLocation) {
            // only want to render Traffic Location elements
            return $content;
        }
        $view = $element->getView();
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('ViewHelper decorator cannot render without a registered view object');
        }

        $separator     = $this->getSeparator();
        $id            = $element->getId();
        $name            = $element->getFullyQualifiedName();
        
        $value = $element->getValue();

        $stateSelect = new Zend_Form_Element_Select('state_id', array(
			'label' => 'State',
			'multiOptions' => $element->getStateOptions(),
			'decorators' => array(
				'ViewHelper'
			), 
			'value' => $element->getState()
		));
		
		if(!$value){
			$citySelect = $view->formSelect('city_id', null, array('disabled'=>true), array(''=>'Select State First'));
		}else{
	        $citySelect = new Zend_Form_Element_Select('city_id', array(
				'multiOptions' => $element->getCityOptions(),
				'decorators' => array(
					'ViewHelper'
				), 
				'value' => $element->getCity()
			));
		}
		
		if(!$value){
			$locationSelect = $view->formSelect($name, null, array('disabled'=>true), array(''=>'Select Country, State and City First'));
		}else{
	        $locationSelect = new Zend_Form_Element_Select($name, array(
				'multiOptions' => $element->getLocationOptions(),
				'decorators' => array(
					'ViewHelper'
				), 
				'value' => $value
			));
		}
		
        $markup = "<table>
        	<tr>
        		<td>Country:{$view->formSelect('country_id', null, null, array('NG'=>'Nigeria'))}</td>
        		<td>State:{$stateSelect}</td>
        		<td>City:{$citySelect}</td>
        	</tr>
        	<tr>
        		<td colspan='3'>Location:{$locationSelect}</td>
        	</tr>
        </table>";
        
       	$view->headScript()->appendFile(
       		'/js/jquery.js',
       		'text/javascript'
       	); 
       	
       	$markup .= "<script language='javascript' src='/js/jquery.cascaded.select.js'></script>";
       	$markup .= "<script language='javascript'>			
       		$(function(){
       			init();
			}); 

			function init(){
				$('#state_id').cascade({
					cascaded: 'city_id', 
					source:'/async/select-city', 
					dependentStartingLabel:'Select City', 
					spinnerImg:'/styles/img/loading.gif',
					dependentNothingFoundLabel:'No Cities Found'
    			});
    			
				$('#city_id').cascade({
					cascaded: '{$id}', 
					source:'/async/select-location', 
					dependentStartingLabel:'Select Traffic Location', 
					spinnerImg:'/styles/img/loading.gif',
					dependentNothingFoundLabel:'No Traffic Locations Found'
    			});
			}
        </script>";
    
        
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
    }
}