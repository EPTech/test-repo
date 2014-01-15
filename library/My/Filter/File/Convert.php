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
class My_Filter_File_Convert implements Zend_Filter_Interface
{
	protected $_convertTo;
	
	public function __construct($type)
	{
        $this->setType($type);
	}
	
	public function setType($to){
		$this->_convertTo = $to;
	}
	
	public function getType(){
		return $this->_convertTo;
	}

    /**
     * Defined by Zend_Filter_Interface
     *
     * Renames the file $value to the new name set before
     * Returns the file $value, removing all but digit characters
     *
     * @param  string $value Full path of file to change
     * @throws Zend_Filter_Exception
     * @return string The new filename which has been set, or false when there were errors
     */
    public function filter($value)
    {
    	$type = $this->getType();
    	
    	if (($img_info = getimagesize($value)) === FALSE)
    		die("Image not found or not an image");
    	
    	if($img_info[2] === $type){
    		return;
    	}
    	
    	switch ($img_info[2]) {
    		case IMAGETYPE_GIF  : $src = imagecreatefromgif($value);  break;
    		case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($value); break;
    		case IMAGETYPE_PNG  : $src = imagecreatefrompng($value);  break;
    		default : die("Unknown filetype");
    	}
    	
    	$pathInfo = pathinfo($value);
    	$convert =  $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.jpg';
    	
    	switch ($type) {
    		case IMAGETYPE_GIF  : return imagegif($src, $convert);  break;
    		case IMAGETYPE_JPEG : return imagejpeg($src, $convert, 100); break;
    		case IMAGETYPE_PNG  : return imagepng($src, $convert, 9);  break;
    		default : die("Unknown filetype");
    	}
    	
    	
    	if(file_exists($value)){
    		unlink($value);
    	}
    	
    	return $value;
    }
}
