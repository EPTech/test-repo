<?php

class My_Form_Decorator_Map extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_Map) {
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
        $id = $element->getId();
        
        //set styles and scripts
        $view->headScript()->appendFile(
        	'/js/jquery.js',
        	'text/javascript'
        );

        $markup = "<a class='getGMap' href=''>Get Path From Map</a>
        <script language='javascript'>
			function loadMap() {
				var address = $('#city_id option[value='+$('#city_id').val()+']').text()
							+ ', ' + $('#state_id option[value='+$('#state_id').val()+']').text()
							+ ', ' + $('#country_id option[value='+$('#country_id').val()+']').text();
							
				updateStatus('Setting address: '+address);
				
		    	var geocoder = new google.maps.Geocoder();
					
		          
		        geocoder.geocode({ 'address': address }, function (results, status) {
		         	loc = null;
		          	
		            if (status == google.maps.GeocoderStatus.OK) {
		                point = results[0].geometry.location;
		            }
		            else{
				    	//updateSatus('Address not valid. Centralizing in Abuja.');
				    	point = new google.maps.LatLng(9.06595, 7.48271);
				    }
		                
		            getMap();
		        });
		    }
		      
			function getMap() {
				var myOptions = {
					zoom: 16,
					center: point,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				    
				var map = new google.maps.Map(document.getElementById('map'), myOptions);
				
				map.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlDiv);
									
				updateStatus('Drag the red marker to location or click location on map to set location.');
				      
	            google.maps.event.addDomListener(controlDiv, 'click', function() {     
	            	set();
					$.fn.overlay.close(controlDiv);
				});
				
				
				var polyOptions = {
				    strokeColor: '#000000',
				    editable: true,
				    strokeOpacity: 1.0,
				    strokeWeight: 3
				} 
				
				poly = new google.maps.Polyline(polyOptions);
				poly.setMap(map);
				
				google.maps.event.addListener(map, 'click', function(event){
					path = poly.getPath();
				  	path.push(event.latLng);
				});	
				
				google.maps.LatLng.prototype.kmTo = function(a){ 
				    var e = Math, ra = e.PI/180; 
				    var b = this.lat() * ra, c = a.lat() * ra, d = b - c; 
				    var g = this.lng() * ra - a.lng() * ra; 
				    var f = 2 * e.asin(e.sqrt(e.pow(e.sin(d/2), 2) + e.cos(b) * e.cos 
				    (c) * e.pow(e.sin(g/2), 2))); 
				    return f * 6378.137; 
				}
				
				google.maps.Polyline.prototype.inKm = function(n){ 
				    var a = this.getPath(n), len = a.getLength(), dist = 0; 
				    for (var i=0; i < len-1; i++) { 
				       dist += a.getAt(i).kmTo(a.getAt(i+1)); 
				    }
				    return dist; 
				}	
			}
			
			function updateStatus(string) {
				document.getElementById('status').innerHTML = string;
			}
			
			function set(){
				var path = poly.getPath();
				console.log(path);
				$('#{$id}').val(google.maps.geometry.encoding.encodePath(path));
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
	            	
	            	//TODO move this to layout
        			$.getScript('/js/jquery.overlay.js', function(data, textStatus, jqxhr) {
        				if(jqxhr.status == 200){
						   	$.getScript('/js/jquery.centralize.js', function(data, textStatus, jqxhr) {
        						if(jqxhr.status == 200){
					            	content.overlay({
					            		parentSelector : '#body #display',
										loadContainer  : false
									});
								}else{
									console.log(textStatus);
								}
								
							});
						}else{
							console.log(textStatus);
						}
					});
							
					try{
						loadMap();
					}catch(err){
						if(layout.DEBUG){
							console.log(err);
						}
						
						$.getScript('http://maps.googleapis.com/maps/api/js?sensor=false&v=3&key=AIzaSyB-BmaW-Zb4qfytr21VFm5Ov5Uxn3zUXU0&libraries=geometryasync=2&callback=loadMap');
					}
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