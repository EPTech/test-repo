<?php
  
class My_View_Helper_Truncate extends Zend_View_Helper_Abstract
{
    public function oldtruncate($string, $start = 0, $length = 100, $prefix = '...', $postfix = '...')
    {
        $truncated = trim($string);
        $start = (int) $start;
        $length = (int) $length;
         
        // Return original string if max length is 0
        if ($length < 1) return $truncated;
         
        $full_length = iconv_strlen($truncated);
         
        // Truncate if necessary
        if ($full_length > $length) {
            // Right-clipped
            if ($length + $start > $full_length) {
                $start = $full_length - $length;
                $postfix = '';
            }
             
            // Left-clipped
            if ($start == 0) $prefix = '';
             
            // Do truncate!
            $truncated = $prefix . trim(substr($truncated, $start, $length)) . $postfix;
        }       
        return $truncated;
    }
    
    public function truncate($string, $length, $stopanywhere=false) {
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    if (strlen($string) > $length) {
        //limit hit!
        $string = substr($string,0,($length -3));
        if ($stopanywhere) {
            //stop anywhere
            $string .= '...';
        } else{
            //stop on a word.
            $string = substr($string,0,strrpos($string,' ')).'...';
        }
    }
    return $string;
}
}
