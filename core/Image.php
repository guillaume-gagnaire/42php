<?php

class           Image {
	private    	$source;
	private    	$path;
	private   	$filename;
	private    	$extension;
	public     	$size;
	private    	$originalFilename;
	public 		$ok;

	public function  __construct($filename) {
		$this->originalFilename = $filename;
		list($path, $file, $extension) = Image::parseFilename($filename);
		$this->path = $path;
		$this->filename = $file;
		$this->extension = $extension;
		list($source, $size) = Image::info($filename);
		$this->source = $source;
		$this->size = $size;
		$this->ok = !$source ? false : true;
	}
	
	public static function cmyk2rgb($file) {
	    $mgck_wnd = NewMagickWand();
	    MagickReadImage($mgck_wnd, $file);
	
	    $img_colspc = MagickGetImageColorspace($mgck_wnd);
	    if ($img_colspc == MW_CMYKColorspace) {
	        echo "$file was in CMYK format<br />";
	        MagickSetImageColorspace($mgck_wnd, MW_RGBColorspace);
	    }
	    MagickWriteImage($mgck_wnd, $file);
	}
	
	public static function tiff2jpg($file) {
	    $mgck_wnd = NewMagickWand();
	    MagickReadImage($mgck_wnd, $file);
	
	    $img_colspc = MagickGetImageColorspace($mgck_wnd);
	    if ($img_colspc == MW_CMYKColorspace) {
	        echo "$file was in CMYK format<br />";
	        MagickSetImageColorspace($mgck_wnd, MW_RGBColorspace);
	    }
	    MagickSetImageFormat($mgck_wnd, 'JPG');
	    MagickWriteImage($mgck_wnd, str_replace(array('.tif', '.tiff'), '.jpg', $file));
	}
	
	public static function to300dpi($file) {
	    $mgck_wnd = NewMagickWand();
	    MagickReadImage($mgck_wnd, $file);
	    $img_units = MagickGetImageUnits($mgck_wnd);
	    switch ($img_units) {
	        case MW_UndefinedResolution: $units= 'undefined'; break;
	        case MW_PixelsPerInchResolution: $units= 'PPI'; break;
	        case MW_PixelsPerCentimeterResolution: $units= 'PPcm'; break;
	    }
	    list($x_res, $y_res) = MagickGetImageResolution($mgck_wnd);
	    echo "$file<br /> x_res=$x_res $units - y_res=$y_res $units<br />";
	    if($x_res == 300 && $y_res == 300 && $img_units == MW_PixelsPerInchResolution) {return; }
	    MagickSetImageResolution($mgck_wnd, 300 , 300);
	    MagickSetImageUnits($mgck_wnd, MW_PixelsPerInchResolution);
	    MagickWriteImage($mgck_wnd, $file);
	}

	public function  save($dest = null, $quality = 99) {
		if ($dest == null) {
			$path = $this->path.'/'.$this->filename.'.'.$this->extension;
			$extension = $this->extension;
		} else {
			$path = $dest;
			list( , , $extension) = Image::parseFilename($dest);
		}
		if (!$this->source)
			return;
		switch (strtolower($extension)) {
		case 'jpeg':
		case 'jpg':
			imagejpeg($this->source, $path, $quality);
			break;
		case 'gif':
			imagegif($this->source, $path);
			break;
		case 'png':
			imagepng($this->source, $path, intval($quality / 10));
			break;
		default:
			trigger_error('This extension is not supported yet ('.$extension.').');
			break;
		}
	}

	public function 	orientation() {
		if ($this->size['height'] > $this->size['width'])
			return 'portrait';
		return 'landscape';
	}

	public function  updateSize() {
		$this->size = array(
			'height' => imagesy($this->source),
			'width'  => imagesx($this->source)
		);
	}

	public function   exifRotation() {
		if (!$this->source)
			return $this;
		$exif = @exif_read_data($this->originalFilename, 'IFD0');
		if(!empty($exif['Orientation'])) { switch($exif['Orientation']) {
			case 8:
				$this->source = imagerotate($this->source, 90, 0);
				$this->updateSize();
				break;
			case 3:
				$this->source = imagerotate($this->source, 180, 0);
				$this->updateSize();
				break;
			case 6:
				$this->source = imagerotate($this->source, -90, 0);
				$this->updateSize();
				break;
			}
		}
		return $this;
	}

	public function  antialias($enabled = true) {
		if (!$this->source)
			return $this;
		imageantialias($this->source, $enabled);
		return $this;
	}

	public function  crop($width, $height, $canBeHigher = true) {
		if (!$this->source)
			return $this;
		if ($height > $this->size['height'] && $width > $this->size['width'] && !$canBeHigher)
			return $this;
		$newSize = Image::newSize($this->size['width'], $this->size['height'], $width, $height, true);
		$image = imagecreatetruecolor($width, $height);
		
		if (in_array(strtolower($this->extension), ['png', 'gif'])) {
			imagecolortransparent($image, imagecolorallocatealpha($image, 0, 0, 0, 127));
			imagealphablending($image, false);
			imagesavealpha($image, true);
		}
		
		if ($newSize['side'] == 'left')
			imagecopyresampled($image, $this->source, $newSize['cropped'], 0, 0, 0, $newSize['width'], $newSize['height'], $this->size['width'], $this->size['height']);
		else
			imagecopyresampled($image, $this->source, 0, $newSize['cropped'], 0, 0, $newSize['width'], $newSize['height'], $this->size['width'], $this->size['height']);
		$this->source = $image;
		$this->size = [
			'height' => $height,
			'width' => $width
		];
		return $this;
	}

	public function  resize($width, $height, $canBeHigher = true) {
		if (!$this->source)
			return $this;
		if ($height > $this->size['height'] && $width > $this->size['width'] && !$canBeHigher)
			return $this;
		$newSize = Image::newSize($this->size['width'], $this->size['height'], $width, $height, false);
		$image = imagecreatetruecolor($newSize['width'], $newSize['height']);
		
		if (in_array(strtolower($this->extension), ['png', 'gif'])) {
			imagecolortransparent($image, imagecolorallocatealpha($image, 0, 0, 0, 127));
			imagealphablending($image, false);
			imagesavealpha($image, true);
		}
		
		if ($newSize['side'] == 'left')
			imagecopyresampled($image, $this->source, $newSize['cropped'], 0, 0, 0, $newSize['width'], $newSize['height'], $this->size['width'], $this->size['height']);
		else
			imagecopyresampled($image, $this->source, 0, $newSize['cropped'], 0, 0, $newSize['width'], $newSize['height'], $this->size['width'], $this->size['height']);
		$this->source = $image;
		$this->size = [
			'height' => $newSize['height'],
			'width' => $newSize['width']
		];
		return $this;
	}

	public static function newSize($actualWidth, $actualHeight, $maxWidth, $maxHeight, $crop = false) {
		$ratioHeight = $maxHeight / $actualHeight;
		$ratioWidth = $maxWidth / $actualWidth;
		if ((!$crop && $ratioHeight > $ratioWidth) || ($crop && $ratioHeight < $ratioWidth)) {
			$newWidth = $actualWidth * $ratioWidth;
			$newHeight = $actualHeight * $ratioWidth;
			$side = 'top';
			$cropped = -($newHeight - $maxHeight) / 2;
		} else {
			$newWidth = $actualWidth * $ratioHeight;
			$newHeight = $actualHeight * $ratioHeight;
			$side = 'left';
			$cropped = -($newWidth - $maxWidth) / 2;
		}
		if ($crop)
			return ['height' => $newHeight, 'width' => $newWidth, 'cropped' => $cropped, 'side' => $side];
		else
			return ['height' => $newHeight, 'width' => $newWidth, 'cropped' => 0, 'side' => $side];
	}

	public static function imageCreateFromAny($filepath) { 
	    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
	    $allowedTypes = array( 
	        1,  // [] gif 
	        2,  // [] jpg 
	        3,  // [] png ,
	        6 	// [] bmp
	    ); 
	    if (!in_array($type, $allowedTypes)) { 
	        return false; 
	    } 
		$im = false;
	    switch ($type) { 
	        case 1 : 
	            $im = imageCreateFromGif($filepath); 
				break; 
	        case 2 : 
	            $im = imageCreateFromJpeg($filepath); 
				break; 
	        case 3 : 
	            $im = imageCreateFromPng($filepath); 
				break; 
	        case 6 : 
	            $im = imageCreateFromWbmp($filepath); 
				break; 
	        default:
	        	trigger_error('This extension is not supported yet.');
	        	break;
	    }    
	    return $im;  
	} 

	public static function info($filename) {
		// Process size
		$sizes = getimagesize($filename);
		$size = [
		'height' => $sizes[1],
		'width' => $sizes[0]
		];
		
		
		$source = self::imageCreateFromAny($filename);
		
		
		return [$source, $size];
	}

	public static function parseFilename($filename) {
		// Process path
		$path = explode('/', $filename);
		$filename = $path[sizeof($path) - 1];
		unset($path[sizeof($path) - 1]);
		$path = implode('/', $path);

		// Process filename
		$filename = explode('.', $filename);
		$extension = $filename[sizeof($filename) - 1];
		unset($filename[sizeof($filename) - 1]);
		$filename = implode('.', $filename);

		return [$path, $filename, $extension];
	}
}

?>