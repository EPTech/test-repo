<?php

class My_Form_Decorator_Locations extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_Locations) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $value = $element->getValue() ? $element->getValue() : array();
        $name = $element->getFullyQualifiedName();
        $attribs = $element->getAttribs();
        
        $valueLiMask = 
        '<li>
        	<input type="hidden" name="'.$name.'[%key%][title]" value="%title%"/>
        	<input type="hidden" name="'.$name.'[%key%][country_id]" value="%country_id%"/>
        	<input type="hidden" name="'.$name.'[%key%][state_id]" value="%state_id%"/>
        	<input type="hidden" name="'.$name.'[%key%][address]" value="%address%"/>
        	<input type="hidden" name="'.$name.'[%key%][email]" value="%email%"/>
        	<input type="hidden" name="'.$name.'[%key%][longitude]" value="%longitude%"/>
        	<input type="hidden" name="'.$name.'[%key%][latitude]" value="%latitude%"/>
        	<h3>%title%</h3>
        	<div>%address%, %state%, %country%</div>
        	<div>%email%</div>
        	<div>%longitude%, %latitude%</div>
        	<div><a class="delete" href="">Delete</a></div>
        </li>';
        
        $valueLi = null;
        
        foreach ($value as $key => $location) {
        	$valueLi .= str_replace(
        		array('%key%','%title%','%country_id%','%country%','%state_id%','%state%','%address%','%email%','%longitude%','%latitude%'), 
        		array($key,$location['title'], $location['country_id'], $location['country_id'], $location['state_id'], $location['state_id'], $location['address'], $location['email'], $location['longitude'], $location['latitude']), 
        		$valueLiMask
        	);
        }
 
        $valueMarkup = 
        '<ul class="locations" style="float:left; padding: 20px; margin: 0;">'
        	. $valueLi
        . '</ul>';
                
        $inputMarkupMask = '<dt>%label%</dt><dd>%input%</dd>';
        
        $geoLocationMask = '<div style="display:inline-block">
	        					<div style="float:left">%longitude%
	        						<div>
	        							<small>longitude</small>
	        						</div>
	        					</div>
	        					<div style="float:left">%latitude%
	        						<div>
	        							<small>latitude</small>
	        						</div>
	        					</div>
	        				</div>';
               
        $inputMarkup = '<dl id="inputs" style="float:left; padding: 20px; margin: 0;"><h3>Enter Location</h3>';
        $inputMarkup .= str_replace(
        	array('%label%', '%input%'), array('Title', $view->formText('_title', null)), $inputMarkupMask
        );
        $inputMarkup .= str_replace(
        	array('%label%', '%input%'), array('Country', $view->formText('_country_id', null)), $inputMarkupMask
        );
        $inputMarkup .= str_replace(
        	array('%label%', '%input%'), array('State', $view->formText('_state_id', null)), $inputMarkupMask
        );
        $inputMarkup .= str_replace(
        	array('%label%', '%input%'), array('Address', $view->formText('_address', null)), $inputMarkupMask
        );
        $inputMarkup .= str_replace(
        	array('%label%', '%input%'), array('Email Address', $view->formText('_email', null)), $inputMarkupMask
        );
        //markup geo location form fields
        $geoLocationMarkup = str_replace(
        	array('%longitude%', '%latitude%'), array($view->formText('_longitude', null), $view->formText('_latitude', null)), $geoLocationMask
        );
        
        $inputMarkup .= str_replace(
        	array('%label%', '%input%'), array('Geo-Location <a href="#"><small>[Use Map]</small></a>', $geoLocationMarkup), $inputMarkupMask
        );
        $inputMarkup .= str_replace(
        	array('%label%', '%input%'), array('&nbsp;', $view->formButton('addLocationBtn', 'Add Location')), $inputMarkupMask
        );
        $inputMarkup .= '</dl>';

        $markup = '<div id="'.$name.'" class="locations-widget" style="display:inline-block">' . $valueMarkup . $inputMarkup . '</div>';
        
        $jsValueLiMask = preg_replace('/\n\s+/', '', $valueLiMask);
        
        //var_dump($jsValueLiMask); die;
        //set styles and scripts
        $view->headScript()->appendFile(
        	'/js/jquery.js',
        	'text/javascript'
        );
        
        $view->headScript()->appendScript("
        	$(function(){
				$('#addLocationBtn').click(function(){
					//alert('');
					var valueLiMask = '{$jsValueLiMask}';
					var \$inputs = $('#{$name}.locations-widget #inputs');
					
					var titleValue = $('input[name=_title]', \$inputs).val();
					var countryIdValue = $('input[name=_country_id]', \$inputs).val();
					var stateIdValue = $('input[name=_state_id]', \$inputs).val();
					var addressValue = $('input[name=_address]', \$inputs).val();
					var emailValue = $('input[name=_email]', \$inputs).val();
					var longitudeValue = $('input[name=_longitude]', \$inputs).val();
					var latitudeValue = $('input[name=_latitude]', \$inputs).val();
					var key = \$('#{$name}.locations-widget .locations').children().size();
					
					var valueLi = valueLiMask.replace(/%key%/g, key)
											.replace(/%title%/g, titleValue)
											.replace(/%country_id%/g, countryIdValue)
											.replace(/%country%/g, countryIdValue)
											.replace(/%state_id%/g, stateIdValue)
											.replace(/%state%/g, stateIdValue)
											.replace(/%address%/g, addressValue)
											.replace(/%email%/g, emailValue)
											.replace(/%longitude%/g, longitudeValue)
											.replace(/%latitude%/g, latitudeValue);
					
					//alert(valueLi);
					$('#{$name}.locations-widget .locations').append(valueLi);
					
					//clear input values
					$(':input', \$inputs).val('');
				});
				
				$('a.delete').live('click', function(e){
					e.preventDefault();
					$(this).parents('li').remove();
				});
			});
        ");
        
        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
    }
}