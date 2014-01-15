<?php
class My_Form_Element_Db_Multiselect extends My_Form_Element_Db_Select
{
    /**
     * 'multiple' attribute
     * @var string
     */
    public $multiple = 'multiple';

    /**
     * Multiselect is an array of values by default
     * @var bool
     */
    protected $_isArray = true;
}