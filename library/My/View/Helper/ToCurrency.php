<?php

class My_View_Helper_ToCurrency extends Zend_View_Helper_Abstract {

    public function toCurrency($value, $locale = 'ha_NG') {
        $currency = new Zend_Currency($locale);
        
        return $currency->setValue($value);
    }

}
