<?php

class My_View_Helper_SearchBox extends Zend_View_Helper_Abstract{
    public static function searchBox($action = null, $method="get", $options=null)
    {
        $attribs = null;
        
        foreach($options as $attr => $value){
            $attribs .= " {$attr}=\"{$value}\" ";
        }
        
        $searchBoxMarkup = '<form action="'.$action.'" method="'.$method.'" '.$attribs.'>
            <input name="q" id="q" type="text" value=""/>
            <input name="" type="submit" value="Search"/>
        </form>';
        
        return $searchBoxMarkup;
    }
	
}
