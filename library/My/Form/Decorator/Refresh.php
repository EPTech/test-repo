<?php

class My_Form_Decorator_Refresh extends Zend_Form_Decorator_Abstract {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function render($content) {
        $element = $this->getElement();
        if (!$element instanceof My_Form_Element_Db_Select) {
            // only want to refresh Select elements
            return $content;
        }

        $view = $element->getView();
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('ViewHelper decorator cannot render without a registered view object');
        }

        $separator = $this->getSeparator();
        $id = $element->getId();

        $markup = '<a id="'.$id.'-refresh" href="javascript:void();">Refresh</a>';

        $view->headScript()->appendFile(
                '/js/jquery.js', 'text/javascript'
        )->appendFile(
                '/js/jquery.refresh.select.js', 'text/javascript'
        )->appendScript("
        	$(function(){
                    $('#{$id}-refresh').refresh('{$id}', '{$this->getOption('url')}', { 
                        spinnerImg:'/theme/images/loading.gif',
                        ddlStartingLabel: '{$element->getMultiOption('')}',
                        params:{
                            '_k':'{$this->getOption('key')}'
                        }
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