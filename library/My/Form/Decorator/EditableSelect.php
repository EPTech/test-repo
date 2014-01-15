<?php

class My_Form_Decorator_EditableSelect extends Zend_Form_Decorator_ViewHelper {

    public function render($content) {
        $element = $this->getElement();

        $view = $element->getView();
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('ViewHelper decorator cannot render without a registered view object');
        }

        if (method_exists($element, 'getMultiOptions')) {
            $element->getMultiOptions();
        }

        $separator = $this->getSeparator();
        $value = $this->getValue($element);
        $attribs = $this->getElementAttribs();
        $name = $element->getFullyQualifiedName();
        $id = $element->getId();
        $attribs['id'] = $id;
        $addUrl = $element->getAddUrl();
        $editUrl = $element->getEditUrl();

        $helperObject = $view->formSelect($name, $value, $attribs, $element->options);

        if (method_exists($helperObject, 'setTranslator')) {
            $helperObject->setTranslator($element->getTranslator());
        }

        $elementContent = '<div style="whitespace: nowrap;">' . $helperObject . ' [<a href="' . $editUrl . '">Edit</a>]</div>';

        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $separator . $elementContent;
            case self::PREPEND:
                return $elementContent . $separator . $content;
            default:
                return $elementContent;
        }
    }

}