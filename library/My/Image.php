<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author TUNDE
 */
class My_Image {

	private $image;
	private $image_type;
	private $_width;
	private $_height;
	private $_filename;

	public function __construct($filename) {
		$this->_filename = $filename;
		$this->load($this->_filename);
	}
	
	public function getType(){
		return $this->image_type;
	}

	private function load($filename) {
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];

		if ($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif ($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif($filename);
		} elseif ($this->image_type == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng($filename);
		}

		$this->_width = $image_info[0];
		$this->_height = $image_info[1];
	}

	function save($fileName = null, $imgType=null, $compression=75, $permissions=null) {
		if(!$imgType){
			$imgType = $this->image_type;
		}
		
		if(!$fileName){
			$fileName = $this->_filename;
		}
		
		if ($imgType == IMAGETYPE_JPEG) {
			imagejpeg($this->image, $fileName, $compression);
		} elseif ($imgType == IMAGETYPE_GIF) {
			imagegif($this->image, $fileName);
		} elseif ($imgType == IMAGETYPE_PNG) {
			imagepng($this->image, $fileName);
		}
		
		if ($permissions != null) {
			chmod($fileName, $permissions);
		}
	}

	public function fill($width, $height, $R, $G, $B) {
		$substrate = imagecreatetruecolor($width, $height);
		$backgroundColor = imagecolorallocate($substrate, $R, $G, $B);
		imagefill($substrate, 0, 0, $backgroundColor);
		imagecopy($substrate, $this->image, (($width - $this->_width) / 2), (($height - $this->_height) / 2), 0, 0, $this->_width, $this->_height);
		imagedestroy($this->image);
		$this->image = $substrate;
	}

	function output($name=null, $image_type=IMAGETYPE_JPEG) {
		if ($name)
			header('Content-Disposition: inline; filename="' . $name . '"');
		if ($this->image_type == IMAGETYPE_JPEG) {
			header('Content-Type: image/jpeg');
			imagejpeg($this->image, null, 100);
		} elseif ($this->image_type == IMAGETYPE_GIF) {
			header('Content-Type: image/gif');
			imagegif($this->image);
		} elseif ($this->image_type == IMAGETYPE_PNG) {
			header('Content-Type: image/png');
			imagepng($this->image, null, 9);
		}
	}

	public function & getImage() {
		return $this->image;
	}

	function getWidth() {
		return imagesx($this->image);
	}

	function getHeight() {
		return imagesy($this->image);
	}

	function resizeToBoundary($boundary) {//var_dump($boundary); die;
		if (is_array($boundary)) {
			$thumb_width = $boundary[0];
			$thumb_height = isset($boundary[1]) ? $boundary[1] : $thumb_width;

			$width = $this->getWidth();
			$height = $this->getHeight();

			$sourceRatio = $width / $height;
			$targetRatio = $thumb_width / $thumb_height;

			if ($sourceRatio >= $targetRatio) {
				$scale = $width / $thumb_width;
			} else {
				$scale = $height / $thumb_height;
			}

			$resizeWidth = (int) ($width / $scale);
			$resizeHeight = (int) ($height / $scale);

			$this->resize($resizeWidth, $resizeHeight);
		}
	}

	function resizeToHeight($height) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}

	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
	}

	function scale($scale) {
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width, $height);
	}

	function resize($width, $height) {
		$new_image = imagecreatetruecolor($width, $height);
		$white = imagecolorallocatealpha($new_image, 255, 255, 255, 0);
		imagefill($new_image, 0, 0, $white);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		imagedestroy($this->image);
		$this->image = $new_image;
	}

}
