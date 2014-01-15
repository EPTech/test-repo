<?php

class My_View_Helper_LiteralizeTS extends Zend_View_Helper_Abstract{
    public function literalizeTS($ts)
    {
    	$diff = time() - $ts;
    	$date = new Zend_Date($ts, Zend_Date::TIMESTAMP);
    	
    	if($date->isToday()){
	    	if($diff < 60){
	    		$time = $diff;
	    		
	    		if($time > 1)
	    			return "{$time} seconds ago";
	    		else
	    			return "a second ago";
	    	}elseif($diff < 3600){
	    		$time = (int)($diff/60);
	    		
	    		if($time > 1)
	    			return "{$time} minutes ago";
	    		else
	    			return "a minute ago";
	    	}elseif($diff < 86400){
	    		$time = (int)($diff/3600);
	    		
	    		if($time > 1)
	    			return "{$time} hours ago";
	    		else
	    			return "an hour ago";
	    	}else{
	    		return "Today at ".date('h:i A', $ts);
	    	}
    	}
    	
    	if($date->isYesterday()){
    		$time = date('h:i A', $ts);
    		
    		return "Yesterday at {$time}";
    	}
    	
    	return date('D jS F h:i A', $ts);
    }
	
}
