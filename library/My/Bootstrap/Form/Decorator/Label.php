<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/Abstract.php';

/**
 * Zend_Form_Decorator_Label
 *
 * Accepts the options:
 * - separator: separator to use between label and content (defaults to PHP_EOL)
 * - placement: whether to append or prepend label to content (defaults to prepend)
 * - tag: if set, used to wrap the label in an additional HTML tag
 * - opt(ional)Prefix: a prefix to the label to use when the element is optional
 * - opt(iona)lSuffix: a suffix to the label to use when the element is optional
 * - req(uired)Prefix: a prefix to the label to use when the element is required
 * - req(uired)Suffix: a suffix to the label to use when the element is required
 *
 * Any other options passed will be used as HTML attributes of the label tag.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Decorator
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Label.php 23960 2011-05-03 10:58:52Z yoshida@zend.co.jp $
 */
class My_Bootstrap_Form_Decorator_Label extends Zend_Form_Decorator_HtmlTag
{
    
    const INLINE = 'INLINE';
	
    /**
     * Default placement: inline
     * @var string
     */
    protected $_placement = 'INLINE';
	
    public function render($content)
    {
        $tag       = $this->getTag();
		$tag = $tag ? $tag : 'label';
        $placement = $this->getPlacement();
        $noAttribs = $this->getOption('noAttribs');
        $openOnly  = $this->getOption('openOnly');
        $closeOnly = $this->getOption('closeOnly');
		$element = $this->getElement();
        $this->removeOption('noAttribs');
        $this->removeOption('openOnly');
        $this->removeOption('closeOnly');

        $attribs = null;
        if (!$noAttribs) {
            $attribs = $this->getOptions();
        }
		
        switch ($placement) {
            case self::APPEND:
                return $content
                     . $this->_getOpenTag($tag, $attribs)
					 . $element->getLabel()
                     . $this->_getCloseTag($tag);
            case self::PREPEND:
                return $this->_getOpenTag($tag, $attribs)
					 . $element->getLabel()
                     . $this->_getCloseTag($tag)
                     . $content;
            case self::INLINE:
                return $this->_getOpenTag($tag, $attribs)
                     . $content
					 . $element->getLabel()
                     . $this->_getCloseTag($tag);
        }
    }
}
