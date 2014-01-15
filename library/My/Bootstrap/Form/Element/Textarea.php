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
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Form_Element_Xhtml */
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * Textarea form element
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Textarea.php 23775 2011-03-01 17:25:24Z ralph $
 */
class My_Bootstrap_Form_Element_Textarea extends Zend_Form_Element_Textarea
{
	
	public function loadDefaultDecorators()
	{
		if ($this->loadDefaultDecoratorsIsDisabled()) {
			return $this;
		}
	
		$decorators = $this->getDecorators();
		if (empty($decorators)) {
			$this->addDecorator('ViewHelper')
			->addDecorator('Errors')
			->addDecorator('Description', array('tag' => 'span', 'class' => 'help-block'))
			->addDecorator(array('controls'=>'HtmlTag'), array('tag'=>'div', 'class'=>'controls'))
			->addDecorator('Label', array('class'=>'control-label', 'placement'=>'PREPEND'))
			->addDecorator(array('control-group'=>'HtmlTag'), array('tag'=>'div', 'class'=>'control-group'));
		}
	
		return $this;
	}
}
