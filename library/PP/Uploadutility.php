<?php

/**
 * Application 404 exception
 * 
 */
class PP_Uploadutility {

	// This function will proportionally resize image 
	public function resizeImage($CurWidth, $CurHeight, $MaxSize, $DestFolder, $SrcImage, $Quality, $ImageType) {
		//Check Image size is not 0
		$maxPpWidth = 173;
		$maxPpHeight = 160;
		if ($CurWidth <= 0 || $CurHeight <= 0) {
			return false;
		}

		//Construct a proportional size of new image
		$ImageScale = min($MaxSize / $CurWidth, $MaxSize / $CurHeight);
		$NewWidth = 173; //ceil($ImageScale * $CurWidth);
		$NewHeight = 160; //ceil($ImageScale * $CurHeight);
		$NewCanves = imagecreatetruecolor($NewWidth, $NewHeight);

		// Resize Image
		if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight)) {
			switch (strtolower($ImageType)) {
				case 'image/png':
					if ($CurWidth > $maxPpWidth or $CurHeight > $maxPpHeight) {
						imagepng($NewCanves, $DestFolder);
					} else {
						imagepng($SrcImage, $DestFolder);
					}
					break;
				case 'image/gif':
					if ($CurWidth > $maxPpWidth or $CurHeight > $maxPpHeight) {
						imagegif($NewCanves, $DestFolder);
					} else {
						imagegif($SrcImage, $DestFolder);
					}
					break;
				case 'image/jpeg':
				case 'image/pjpeg':
					if ($CurWidth > $maxPpWidth or $CurHeight > $maxPpHeight) {
						imagejpeg($NewCanves, $DestFolder, $Quality);
					} else {
						imagejpeg($SrcImage, $DestFolder, $Quality);
					}
					break;
				default:
					return false;
			}
			//Destroy image, frees memory	
			if (is_resource($NewCanves)) {
				imagedestroy($NewCanves);
			}
			return true;
		}
	}

//This function corps image to create exact square images, no matter what its original size!
	public function cropImage($CurWidth, $CurHeight, $iSize, $DestFolder, $SrcImage, $Quality, $ImageType) {
		//Check Image size is not 0
		$maxPpWidth = 173;
		$maxPpHeight = 160;

		if ($CurWidth <= 0 || $CurHeight <= 0) {
			return false;
		}

		if ($CurWidth > $CurHeight) {
			$y_offset = 0;
			$x_offset = ($CurWidth - $CurHeight) / 2;
			$square_size = $CurWidth - ($x_offset * 2);
		} else {
			$x_offset = 0;
			$y_offset = ($CurHeight - $CurWidth) / 2;
			$square_size = $CurHeight - ($y_offset * 2);
		}

		$NewCanves = imagecreatetruecolor($iSize, $iSize);
		if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size)) {
			switch (strtolower($ImageType)) {
				case 'image/png':
					if ($CurWidth > $maxPpWidth or $CurHeight > $maxPpHeight) {
						imagepng($NewCanves, $DestFolder);
					} else {
						imagepng($SrcImage, $DestFolder);
					}
					break;
				case 'image/gif':
					if ($CurWidth > $maxPpWidth or $CurHeight > $maxPpHeight) {
						imagegif($NewCanves, $DestFolder);
					} else {
						imagegif($SrcImage, $DestFolder);
					}
					break;
				case 'image/jpeg':
				case 'image/pjpeg':
					if ($CurWidth > $maxPpWidth or $CurHeight >  $maxPpHeight) {
						imagejpeg($NewCanves, $DestFolder, $Quality);
					} else {
						imagejpeg($SrcImage, $DestFolder, $Quality);
					}
					break;
				default:
					return false;
			}
			//Destroy image, frees memory	
			if (is_resource($NewCanves)) {
				imagedestroy($NewCanves);
			}
			return true;
		}
	}

}