<?php
class My_View_Helper_BootstrapRadio extends Zend_View_Helper_FormRadio
{
    
    public function bootstrapRadio($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
		//Zend_Debug::dump($attribs); die();
    	$attribs['label_class'] = array_key_exists('label_class', $attribs) ?  $attribs['label_class'] . ' radio' : 'radio';
        
    	return $this->formRadio($name, $value, $attribs, $options, $listsep);
    }
}
