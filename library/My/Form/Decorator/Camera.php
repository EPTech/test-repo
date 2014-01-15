<?php

class My_Form_Decorator_Camera extends Zend_Form_Decorator_Abstract{
	public function render($content){
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_Camera) {
            // only want to render Date elements
            return $content;
        }
 
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }
 
        $id = $element->getId();
        $value = $element->getValue();
        $name = $element->getFullyQualifiedName();
        $attribs = $element->getAttribs();
       
        $markup = '<div id="'.$id.'">
        	<object class="lfloat" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="320" height="270" id="Camera" align="middle">
				<param name="movie" value="Camera.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="transparent" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="/third-party/Camera/Camera.swf" width="320" height="270">
					<param name="movie" value="Camera.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="always" />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
			<div class="lfloat" id="image"><img src="/theme/images/blank.jpg"/></div>
			<div class="lfloat"><a id="open" href="">Take a picture</a></div>
        </div>';
           
        //set styles and scripts
        $view->headScript()->appendFile(
        	'/js/jquery.js',
        	'text/javascript'
        )->appendScript("
        	$(function(){
        		$(document['Camera']).hide();
        		
        		$('#{$id} #open').click(function(e){
        			e.preventDefault();
					$(document['Camera']).show();
        			$('#{$id} #image').hide();
				});
				
				
        		var form = $($('#{$id}').parents('form').get(0));
        		form.submit(function(e){
        			e.preventDefault();
					console.log(document['Camera'].saveImage());
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