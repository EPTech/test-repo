<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileSize
 *
 * @author TUNDE
 */
class My_View_Helper_FileSize {
	function fileSize($size)
	{
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}
}

?>
