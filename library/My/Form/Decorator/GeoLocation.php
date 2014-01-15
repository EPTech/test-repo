<?php

class My_Form_Decorator_GeoLocation extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_GeoLocation) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $value = $element->getValue() ? $element->getValue() : array();
    	//var_dump($value); die;
        $name = $element->getFullyQualifiedName();
        $attribs = $element->getAttribs();
        
        $geoLocationMask = '<div style="display:inline-block; white-space: nowrap;">
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
	        					<div style="float:left; padding:6px;">
	        						<a href="#" class="getGMap" style="text-decoration: none;">Use Map</a>
	        					</div>
	        				</div>';
        
        //markup geo location form fields
        $markup = str_replace(
        	array('%longitude%', '%latitude%'), array($view->formText($name.'[longitude]', $value['longitude']), $view->formText($name.'[latitude]', $value['latitude'])), $geoLocationMask
        );
        
        //set styles and scripts
        $view->headScript()->appendFile(
        	'/js/jquery.js',
        	'text/javascript'
        );

        $markup .= "
        <script language='javascript'>					
			function loadMap() {
				$.get('/traffic/roads/get-path/i/14',{},function(response, textStatus, jqXHR){
					if(jqXHR.status == 200){
						encodedPath = response;
						$.getScript('http://maps.googleapis.com/maps/api/js?sensor=false&v=3&key=AIzaSyB-BmaW-Zb4qfytr21VFm5Ov5Uxn3zUXU0&async=2&callback=getMap&libraries=geometry');
					}
				});
		    }
		      
			function getMap() {
				var path = google.maps.geometry.encoding.decodePath(encodedPath);
				console.log(path);
								    	
				var myOptions = {
					zoom: 16,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				    
				var map = new google.maps.Map(document.getElementById('map'), myOptions);
				    
	            	
            	// Setup the click event listeners: simply set the map to Chicago.   
	            google.maps.event.addDomListener(controlDiv, 'click', function() {     
	            	set();
					$.fn.overlay.close(controlDiv);
				});
				
				map.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlDiv);
				//geocoder = new GClientGeocoder();
					
				var polyOptions = {
				    strokeColor: '#000000',
				    editable: true,
				    strokeOpacity: 1.0,
				    strokeWeight: 3
				} 
				
				poly = new google.maps.Polyline(polyOptions);
				poly.setPath(path);
				poly.setMap(map);		
			}
			
			function updateStatus(string) {
				document.getElementById('status').innerHTML = string;
			}
			
			function updateLocation(point){
				status = 'Latitude: <span>' + point.lat().toFixed(5) + '</span>' + ', Longitude: <span>' + point.lng().toFixed(5)+'</span>';
				updateStatus(status);
			}
			
			function set(){
				if(point){
					document.getElementById('geo_location-longitude').value = point.lng().toFixed(5);
					document.getElementById('geo_location-latitude').value = point.lat().toFixed(5);
				}else{
					alert('Location not set.');
				}
			}	
        	
            function MapToolBarControls(){
            	if(controlDiv == 'undefined'){
		           	controlDiv = document.createElement('DIV');  
		           	controlDiv.style.margin = '5px';
	           	}
	           	
	           	controlDiv.appendChild(control);
            } 
       
        	$(function(){
				$('.getGMap').click(function(e){
					e.preventDefault();
					
	            	var content = $('<div/>').css({
	            		'position': 'absolute',
	            		'height':'90%',
	            		'width':'80%' ,	            		         		
	            	}).click(function(e){
						e.stopPropagation();
					});
	            	
	            	var mapWidget = $('<div/>').css({
	            		'height':'100%',
	            		'width':'100%' ,
	            		'background':'white' 	            		         		
	            	}).appendTo(content);
	            	
	            	controlDiv = document.createElement('DIV');  
	            	controlDiv.style.margin = '5px';
	            	
	            	var controlUI = document.createElement('DIV'); 
	            	controlUI.style.direction = 'ltr';
	            	controlUI.style.overflow = 'hidden';
	            	controlUI.style.position = 'relative';
	            	controlUI.style.background = '-moz-linear-gradient(center top , rgb(255, 255, 255), rgb(230, 230, 230)) repeat scroll 0% 0% transparent';
	            	controlUI.style.padding = '1px 6px';
	            	controlUI.style.border = '1px solid rgb(113, 123, 135)';
	            	controlUI.style.boxShadow = '0pt 2px 4px rgba(0, 0, 0, 0.4)';
	            	controlUI.style.minWidth = '29px';
	            	controlUI.style.title = 'Click to set current location';
	            	controlUI.style.cursor = 'pointer';
	            	 
	            	controlDiv.appendChild(controlUI);
	            	  
	            	// Set CSS for the control interior. 
	            	var controlText = document.createElement('DIV'); 
	            	controlText.style.fontFamily = 'Arial,sans-serif'; 
	            	controlText.style.color = 'rgb(0, 0, 0)';
	            	controlText.style.MozUserSelect = 'none';
	            	controlText.style.textAlign = 'center';
	            	controlText.style.fontSize = '13px'; 
	            	controlText.innerHTML = 'Get Location'; 
	            	controlUI.appendChild(controlText);
	            	
	            	var mapCanvas = $('<div/>').attr('id','map').css({
	            		'height':'97%',
	            		'width':'100%',
	            		'box-shadow':'0 0 3px'          		
	            	}).appendTo(mapWidget);
	            	
	            	var statusBar = $('<div/>').attr('id','status').css({
	            		'height':'3%',
	            		'width':'100%'          		
	            	}).appendTo(mapWidget);
	            	
	            	$.getScript('/js/jquery.overlay.js', function(data, textStatus, jqxhr) {
        				if(jqxhr.status == 200){
						   	$.getScript('/js/jquery.centralize.js', function(data, textStatus, jqxhr) {
        						if(jqxhr.status == 200){
					            	content.overlay({
					            		parentSelector : '#body #display',
										loadContainer  : false
									});
									
									updateStatus('Drag the red marker to location or click location on map to set location.');
								}else{
									console.log(textStatus);
								}
							});
						}else{
							console.log(textStatus);
						}
					});
					loadMap();
				});				
			});
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