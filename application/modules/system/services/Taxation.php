<?php
/**
 * Default_Service_Taxation
 * 
 * Provides tax calculation service
 * 
 * @category   Default
 * @package    Default_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class System_Service_Taxation
{
    const TAXRATE = 15;
       
    public function addTax($amount)
    {
        $tax = ($amount*self::TAXRATE)/100;
        $amount = round($amount + $tax,2);
        
        return $amount;
    }
}