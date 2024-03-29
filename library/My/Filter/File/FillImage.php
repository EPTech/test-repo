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
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Rename.php 23775 2011-03-01 17:25:24Z ralph $
 */
/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class My_Filter_File_FillImage implements Zend_Filter_Interface {
	private $_width, $_height;
	private $_R, $_G, $_B;

	public function __construct($width, $height, $R=255, $G=255, $B=255) {
		//var_dump($R); die;
		$this->_width = $width;
		$this->_height = $height;
		
		$this->_R = $R;
		$this->_G = $G;
		$this->_B = $B;
	}
	
	public function filter($filename) {
		$image = new My_Image($filename);
		$image->fill($this->_width, $this->_height, $this->_R, $this->_G, $this->_B);
		$image->save();
    	
    	return $filename;
	}

}