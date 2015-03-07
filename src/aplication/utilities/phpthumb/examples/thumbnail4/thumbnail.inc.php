<?php
/**
 * thumbnail.inc.php
 * 
 * @author 		Ian Selby (ian@gen-x-design.com)
 * @copyright 	Copyright 2006
 * @version 	1.1 (PHP4)
 * 
 */

/**
 * PHP class for dynamically resizing, cropping, and rotating images for thumbnail purposes and either displaying them on-the-fly or saving them.
 *
 */
class Thumbnail {
    /**
     * Error message to display, if any
     *
     * @var string
     */
    var $errmsg;
    /**
     * Whether or not there is an error
     *
     * @var boolean
     */
    var $error;
    /**
     * Format of the image file
     *
     * @var string
     */
    var $format;
    /**
     * File name and path of the image file
     *
     * @var string
     */
    var $fileName;
    /**
     * Image meta data if any is available (jpeg/tiff) via the exif library
     *
     * @var array
     */
    var $imageMeta;
    /**
     * Current dimensions of working image
     *
     * @var array
     */
    var $currentDimensions;
    /**
     * New dimensions of working image
     *
     * @var array
     */
    var $newDimensions;
    /**
     * Image resource for newly manipulated image
     *
     * @var resource
     * @access private
     */
    var $newImage;
    /**
     * Image resource for image before previous manipulation
     *
     * @var resource
     * @access private
     */
    var $oldImage;
    /**
     * Image resource for image being currently manipulated
     *
     * @var resource
     * @access private
     */
    var $workingImage;
    /**
     * Percentage to resize image by
     *
     * @var int
     * @access private
     */
    var $percent;
    /**
     * Maximum width of image during resize
     *
     * @var int
     * @access private
     */
    var $maxWidth;
    /**
     * Maximum height of image during resize
     *
     * @var int
     * @access private
     */
    var $maxHeight;

    /**
     * Class constructor
     *
     * @param string $fileName
     * @return Thumbnail
     */
    function Thumbnail($fileName) {
        //make sure the GD library is installed
    	if(!function_exists("gd_info")) {
        	echo 'You do not have the GD Library installed.  This class requires the GD library to function properly.' . "\n";
        	echo 'visit http://us2.php.net/manual/en/ref.image.php for more information';
        	exit;
        }
    	//initialize variables
        $this->errmsg               = '';
        $this->error                = false;
        $this->currentDimensions    = array();
        $this->newDimensions        = array();
        $this->fileName             = $fileName;
        $this->imageMeta			= array();
        $this->percent              = 100;
        $this->maxWidth             = 0;
        $this->maxHeight            = 0;

        //check to see if file exists
        if(!file_exists($this->fileName)) {
            $this->errmsg = 'File not found';
            $this->error = true;
        }
        //check to see if file is readable
        elseif(!is_readable($this->fileName)) {
            $this->errmsg = 'File is not readable';
            $this->error = true;
        }

        //if there are no errors, determine the file format
        if($this->error == false) {
            //check if gif
            if(stristr(strtolower($this->fileName),'.gif')) $this->format = 'GIF';
            //check if jpg
            elseif(stristr(strtolower($this->fileName),'.jpg') || stristr(strtolower($this->fileName),'.jpeg')) $this->format = 'JPG';
            //check if png
            elseif(stristr(strtolower($this->fileName),'.png')) $this->format = 'PNG';
            //unknown file format
            else {
                $this->errmsg = 'Unknown file format';
                $this->error = true;
            }
        }

        //initialize resources if no errors
        if($this->error == false) {
            switch($this->format) {
                case 'GIF':
                    $this->oldImage = ImageCreateFromGif($this->fileName);
                    break;
                case 'JPG':
                    $this->oldImage = ImageCreateFromJpeg($this->fileName);
                    break;
                case 'PNG':
                    $this->oldImage = ImageCreateFromPng($this->fileName);
                    break;
            }

            $size = GetImageSize($this->fileName);
            $this->currentDimensions = array('width'=>$size[0],'height'=>$size[1]);
            $this->newImage = $this->oldImage;
            $this->gatherImageMeta();
        }

        if($this->error == true) {
            $this->showErrorImage();
            break;
        }
    }

    /**
     * Must be called to free up allocated memory after all manipulations are done
     *
     */
    function destruct() {
        if(is_resource($this->newImage)) @ImageDestroy($this->newImage);
        if(is_resource($this->oldImage)) @ImageDestroy($this->oldImage);
        if(is_resource($this->workingImage)) @ImageDestroy($this->workingImage);
    }

    /**
     * Returns the current width of the image
     *
     * @return int
     */
    function getCurrentWidth() {
        return $this->currentDimensions['width'];
    }

    /**
     * Returns the current height of the image
     *
     * @return int
     */
    function getCurrentHeight() {
        return $this->currentDimensions['height'];
    }

    /**
     * Calculates new image width
     *
     * @param int $width
     * @param int $height
     * @return array
     */
    function calcWidth($width,$height) {
        $newWp = (100 * $this->maxWidth) / $width;
        $newHeight = ($height * $newWp) / 100;
        return array('newWidth'=>intval($this->maxWidth),'newHeight'=>intval($newHeight));
    }

    /**
     * Calculates new image height
     *
     * @param int $width
     * @param int $height
     * @return array
     */
    function calcHeight($width,$height) {
        $newHp = (100 * $this->maxHeight) / $height;
        $newWidth = ($width * $newHp) / 100;
        return array('newWidth'=>intval($newWidth),'newHeight'=>intval($this->maxHeight));
    }

    /**
     * Calculates new image size based on percentage
     *
     * @param int $width
     * @param int $height
     * @return array
     */
    function calcPercent($width,$height) {
        $newWidth = ($width * $this->percent) / 100;
        $newHeight = ($height * $this->percent) / 100;
        return array('newWidth'=>intval($newWidth),'newHeight'=>intval($newHeight));
    }

    /**
     * Calculates new image size based on width and height, while constraining to maxWidth and maxHeight
     *
     * @param int $width
     * @param int $height
     */
    function calcImageSize($width,$height) {
        $newSize = array('newWidth'=>$width,'newHeight'=>$height);

        if($this->maxWidth > 0) {

            $newSize = $this->calcWidth($width,$height);

            if($this->maxHeight > 0 && $newSize['newHeight'] > $this->maxHeight) {
                $newSize = $this->calcHeight($newSize['newWidth'],$newSize['newHeight']);
            }

            //$this->newDimensions = $newSize;
        }

        if($this->maxHeight > 0) {
            $newSize = $this->calcHeight($width,$height);

            if($this->maxWidth > 0 && $newSize['newWidth'] > $this->maxWidth) {
                $newSize = $this->calcWidth($newSize['newWidth'],$newSize['newHeight']);
            }

            //$this->newDimensions = $newSize;
        }

        $this->newDimensions = $newSize;
    }

    /**
     * Calculates new image size based percentage
     *
     * @param int $width
     * @param int $height
     */
    function calcImageSizePercent($width,$height) {
        if($this->percent > 0) {
            $this->newDimensions = $this->calcPercent($width,$height);
        }
    }

    /**
     * Displays error image
     *
     */
    function showErrorImage() {
        header('Content-type: image/png');
        $errImg = ImageCreate(220,220);
        $bgColor = imagecolorallocate($errImg,255,255,255);
        $fgColor1 = imagecolorallocate($errImg,255,255,255);
        $fgColor2 = imagecolorallocate($errImg,255,0,0);
        //imagestring($errImg,3,6,6,'Error:',$fgColor2);
        //imagestring($errImg,3,55,6,$this->errmsg,$fgColor1);
        imagepng($errImg);
        imagedestroy($errImg);
    }

    /**
     * Resizes image to maxWidth x maxHeight
     *
     * @param int $maxWidth
     * @param int $maxHeight
     */
    function resize($maxWidth = 0, $maxHeight = 0) {
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;

        $this->calcImageSize($this->currentDimensions['width'],$this->currentDimensions['height']);

		if(function_exists("ImageCreateTrueColor")) {
			$this->workingImage = ImageCreateTrueColor($this->newDimensions['newWidth'],$this->newDimensions['newHeight']);
		}
		else {
			$this->workingImage = ImageCreate($this->newDimensions['newWidth'],$this->newDimensions['newHeight']);
		}

		ImageCopyResampled(
			$this->workingImage,
			$this->oldImage,
			0,
			0,
			0,
			0,
			$this->newDimensions['newWidth'],
			$this->newDimensions['newHeight'],
			$this->currentDimensions['width'],
			$this->currentDimensions['height']
		);

		$this->oldImage = $this->workingImage;
		$this->newImage = $this->workingImage;
		$this->currentDimensions['width'] = $this->newDimensions['newWidth'];
		$this->currentDimensions['height'] = $this->newDimensions['newHeight'];
	}

	/**
	 * Resizes the image by $percent percent
	 *
	 * @param int $percent
	 */
	function resizePercent($percent = 0) {
	    $this->percent = $percent;

	    $this->calcImageSizePercent($this->currentDimensions['width'],$this->currentDimensions['height']);

		if(function_exists("ImageCreateTrueColor")) {
			$this->workingImage = ImageCreateTrueColor($this->newDimensions['newWidth'],$this->newDimensions['newHeight']);
		}
		else {
			$this->workingImage = ImageCreate($this->newDimensions['newWidth'],$this->newDimensions['newHeight']);
		}

		ImageCopyResampled(
			$this->workingImage,
			$this->oldImage,
			0,
			0,
			0,
			0,
			$this->newDimensions['newWidth'],
			$this->newDimensions['newHeight'],
			$this->currentDimensions['width'],
			$this->currentDimensions['height']
		);

		$this->oldImage = $this->workingImage;
		$this->newImage = $this->workingImage;
		$this->currentDimensions['width'] = $this->newDimensions['newWidth'];
		$this->currentDimensions['height'] = $this->newDimensions['newHeight'];
	}

	/**
	 * Crops the image from calculated center in a square of $cropSize pixels
	 *
	 * @param int $cropSize
	 */
	function cropFromCenter($cropSize) {
	    if($cropSize > $this->currentDimensions['width']) $cropSize = $this->currentDimensions['width'];
	    if($cropSize > $this->currentDimensions['height']) $cropSize = $this->currentDimensions['height'];

	    $cropX = intval(($this->currentDimensions['width'] - $cropSize) / 2);
	    $cropY = intval(($this->currentDimensions['height'] - $cropSize) / 2);

	    if(function_exists("ImageCreateTrueColor")) {
			$this->workingImage = ImageCreateTrueColor($cropSize,$cropSize);
		}
		else {
			$this->workingImage = ImageCreate($cropSize,$cropSize);
		}

		imagecopyresampled(
            $this->workingImage,
            $this->oldImage,
            0,
            0,
            $cropX,
            $cropY,
            $cropSize,
            $cropSize,
            $cropSize,
            $cropSize
		);

		$this->oldImage = $this->workingImage;
		$this->newImage = $this->workingImage;
		$this->currentDimensions['width'] = $cropSize;
		$this->currentDimensions['height'] = $cropSize;
	}
	/**
	 * Crops an image from the center with provided dimensions
	 * 
	 * If no height is given, the width will be used as a height, thus creating a square crop
	 * 
	 * @param int $cropWidth
	 * @param int $cropHeight
	 * @return GdThumb
	 */
	public function cropFromCenter5 ($cropWidth, $cropHeight = null)
	{
		if (!is_numeric($cropWidth))
		{
			throw new InvalidArgumentException('$cropWidth must be numeric');
		}
		
		if ($cropHeight !== null && !is_numeric($cropHeight))
		{
			throw new InvalidArgumentException('$cropHeight must be numeric');
		}
		
		if ($cropHeight === null)
		{
			$cropHeight = $cropWidth;
		}
		
		$cropWidth	= ($this->currentDimensions['width'] < $cropWidth) ? $this->currentDimensions['width'] : $cropWidth;
		$cropHeight = ($this->currentDimensions['height'] < $cropHeight) ? $this->currentDimensions['height'] : $cropHeight;
		
		$cropX = intval(($this->currentDimensions['width'] - $cropWidth) / 2);
		$cropY = intval(($this->currentDimensions['height'] - $cropHeight) / 2);
		
		$this->crop($cropX, $cropY, $cropWidth, $cropHeight);
		
		return $this;
	}

	/**
	 * Advanced cropping function that crops an image using $startX and $startY as the upper-left hand corner.
	 *
	 * @param int $startX
	 * @param int $startY
	 * @param int $width
	 * @param int $height
	 */
	function crop($startX,$startY,$width,$height) {
	    //make sure the cropped area is not greater than the size of the image
	    if($width > $this->currentDimensions['width']) $width = $this->currentDimensions['width'];
	    if($height > $this->currentDimensions['height']) $height = $this->currentDimensions['height'];
	    //make sure not starting outside the image
	    if(($startX + $width) > $this->currentDimensions['width']) $startX = ($this->currentDimensions['width'] - $width);
	    if(($startY + $height) > $this->currentDimensions['height']) $startY = ($this->currentDimensions['height'] - $height);
	    if($startX < 0) $startX = 0;
	    if($startY < 0) $startY = 0;

	    if(function_exists("ImageCreateTrueColor")) {
			$this->workingImage = ImageCreateTrueColor($width,$height);
		}
		else {
			$this->workingImage = ImageCreate($width,$height);
		}

		imagecopyresampled(
            $this->workingImage,
            $this->oldImage,
            0,
            0,
            $startX,
            $startY,
            $width,
            $height,
            $width,
            $height
		);

		$this->oldImage = $this->workingImage;
		$this->newImage = $this->workingImage;
		$this->currentDimensions['width'] = $width;
		$this->currentDimensions['height'] = $height;
	}

	/**
	 * Outputs the image to the screen, or saves to $name if supplied.  Quality of JPEG images can be controlled with the $quality variable
	 *
	 * @param int $quality
	 * @param string $name
	 */
	function show($quality=100,$name = '') {
	    switch($this->format) {
	        case 'GIF':
	            if($name != '') {
	                ImageGif($this->newImage,$name);
	            }
	            else {
	               header('Content-type: image/gif');
	               ImageGif($this->newImage);
	            }
	            break;
	        case 'JPG':
	            if($name != '') {
	                ImageJpeg($this->newImage,$name,$quality);
	            }
	            else {
	               header('Content-type: image/jpeg');
	               ImageJpeg($this->newImage,'',$quality);
	            }
	            break;
	        case 'PNG':
	            if($name != '') {
	                ImagePng($this->newImage,$name);
	            }
	            else {
	               header('Content-type: image/png');
	               ImagePng($this->newImage);
	            }
	            break;
	    }
	}

	/**
	 * Saves image as $name (can include file path), with quality of # percent if file is a jpeg
	 *
	 * @param string $name
	 * @param int $quality
	 */
	function save($name,$quality=100) {
	    $this->show($quality,$name);
	}

	/**
	 * Creates Apple-style reflection under image, optionally adding a border to main image
	 *
	 * @param int $percent
	 * @param int $reflection
	 * @param int $white
	 * @param bool $border
	 * @param string $borderColor
	 */
	function createReflection($percent,$reflection,$white,$border = true,$borderColor = '#a4a4a4') {
        $width = $this->currentDimensions['width'];
        $height = $this->currentDimensions['height'];

        $reflectionHeight = intval($height * ($reflection / 100));
        $newHeight = $height + $reflectionHeight;
        $reflectedPart = $height * ($percent / 100);

        $this->workingImage = ImageCreateTrueColor($width,$newHeight);

        ImageAlphaBlending($this->workingImage,true);

        $colorToPaint = ImageColorAllocateAlpha($this->workingImage,255,255,255,0);
        ImageFilledRectangle($this->workingImage,0,0,$width,$newHeight,$colorToPaint);

        imagecopyresampled(
                            $this->workingImage,
                            $this->newImage,
                            0,
                            0,
                            0,
                            $reflectedPart,
                            $width,
                            $reflectionHeight,
                            $width,
                            ($height - $reflectedPart));
        $this->imageFlipVertical();

        imagecopy($this->workingImage,$this->newImage,0,0,0,0,$width,$height);

        imagealphablending($this->workingImage,true);

        for($i=0;$i<$reflectionHeight;$i++) {
            $colorToPaint = imagecolorallocatealpha($this->workingImage,255,255,255,($i/$reflectionHeight*-1+1)*$white);
            imagefilledrectangle($this->workingImage,0,$height+$i,$width,$height+$i,$colorToPaint);
        }

        if($border == true) {
            $rgb = $this->hex2rgb($borderColor,false);
            $colorToPaint = imagecolorallocate($this->workingImage,$rgb[0],$rgb[1],$rgb[2]);
            imageline($this->workingImage,0,0,$width,0,$colorToPaint); //top line
            imageline($this->workingImage,0,$height,$width,$height,$colorToPaint); //bottom line
            imageline($this->workingImage,0,0,0,$height,$colorToPaint); //left line
            imageline($this->workingImage,$width-1,0,$width-1,$height,$colorToPaint); //right line
        }

        $this->oldImage = $this->workingImage;
		$this->newImage = $this->workingImage;
		$this->currentDimensions['width'] = $width;
		$this->currentDimensions['height'] = $newHeight;
	}

	/**
	 * Inverts working image, used by reflection function
	 * 
	 * @access	private
	 */
	function imageFlipVertical() {
	    $x_i = imagesx($this->workingImage);
	    $y_i = imagesy($this->workingImage);

	    for($x = 0; $x < $x_i; $x++) {
	        for($y = 0; $y < $y_i; $y++) {
	            imagecopy($this->workingImage,$this->workingImage,$x,$y_i - $y - 1, $x, $y, 1, 1);
	        }
	    }
	}

	/**
	 * Converts hexidecimal color value to rgb values and returns as array/string
	 *
	 * @param string $hex
	 * @param bool $asString
	 * @return array|string
	 */
	function hex2rgb($hex, $asString = false) {
        // strip off any leading #
        if (0 === strpos($hex, '#')) {
           $hex = substr($hex, 1);
        } else if (0 === strpos($hex, '&H')) {
           $hex = substr($hex, 2);
        }

        // break into hex 3-tuple
        $cutpoint = ceil(strlen($hex) / 2)-1;
        $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

        // convert each tuple to decimal
        $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
        $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
        $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

        return ($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
    }
    
    /**
     * Reads selected exif meta data from jpg images and populates $this->imageMeta with appropriate values if found
     *
     */
    function gatherImageMeta() {
    	//only attempt to retrieve info if exif exists
    	if(function_exists("exif_read_data") && $this->format == 'JPG') {
			$imageData = exif_read_data($this->fileName);
			if(isset($imageData['Make'])) 
				$this->imageMeta['make'] = ucwords(strtolower($imageData['Make']));
			if(isset($imageData['Model'])) 
				$this->imageMeta['model'] = $imageData['Model'];
			if(isset($imageData['COMPUTED']['ApertureFNumber'])) {
				$this->imageMeta['aperture'] = $imageData['COMPUTED']['ApertureFNumber'];
				$this->imageMeta['aperture'] = str_replace('/','',$this->imageMeta['aperture']);
			}
			if(isset($imageData['ExposureTime'])) {
				$exposure = explode('/',$imageData['ExposureTime']);
				$exposure = round($exposure[1]/$exposure[0],-1);
				$this->imageMeta['exposure'] = '1/' . $exposure . ' second';
			}
			if(isset($imageData['Flash'])) {
				if($imageData['Flash'] > 0) {
					$this->imageMeta['flash'] = 'Yes';
				}
				else {
					$this->imageMeta['flash'] = 'No';
				}
			}
			if(isset($imageData['FocalLength'])) {
				$focus = explode('/',$imageData['FocalLength']);
				$this->imageMeta['focalLength'] = round($focus[0]/$focus[1],2) . ' mm';
			}
			if(isset($imageData['DateTime'])) {
				$date = $imageData['DateTime'];
				$date = explode(' ',$date);
				$date = str_replace(':','-',$date[0]) . ' ' . $date[1];
				$this->imageMeta['dateTaken'] = date('m/d/Y g:i A',strtotime($date));
			}
    	}
    }
    
    /**
     * Rotates image either 90 degrees clockwise or counter-clockwise
     *
     * @param string $direction
     */
    function rotateImage($direction = 'CW') {
    	if($direction == 'CW') {
    		$this->workingImage = imagerotate($this->workingImage,-90,0);
    	}
    	else {
    		$this->workingImage = imagerotate($this->workingImage,90,0);
    	}
    	$newWidth = $this->currentDimensions['height'];
    	$newHeight = $this->currentDimensions['width'];
		$this->oldImage = $this->workingImage;
		$this->newImage = $this->workingImage;
		$this->currentDimensions['width'] = $newWidth;
		$this->currentDimensions['height'] = $newHeight;
    }
	/**
	 * Adaptively Resizes the Image
	 * 
	 * This function attempts to get the image to as close to the provided dimensions as possible, and then crops the 
	 * remaining overflow (from the center) to get the image to be the size specified
	 * 
	 * @param int $maxWidth
	 * @param int $maxHeight
	 * @return GdThumb
	 */
	public function adaptiveResize ($width, $height)
	{
		// make sure our arguments are valid
		if (!is_numeric($width) || $width  == 0)
		{
			throw new InvalidArgumentException('$width must be numeric and greater than zero');
		}
		
		if (!is_numeric($height) || $height == 0)
		{
			throw new InvalidArgumentException('$height must be numeric and greater than zero');
		}
		
		// make sure we're not exceeding our image size if we're not supposed to
		if ($this->options['resizeUp'] === false)
		{
			$this->maxHeight	= (intval($height) > $this->currentDimensions['height']) ? $this->currentDimensions['height'] : $height;
			$this->maxWidth		= (intval($width) > $this->currentDimensions['width']) ? $this->currentDimensions['width'] : $width;
		}
		else
		{
			$this->maxHeight	= intval($height);
			$this->maxWidth		= intval($width);
		}
		
		$this->calcImageSizeStrict($this->currentDimensions['width'], $this->currentDimensions['height']);
		
		// resize the image to be close to our desired dimensions
		$this->resize($this->newDimensions['newWidth'], $this->newDimensions['newHeight']);
		
		// reset the max dimensions...
		if ($this->options['resizeUp'] === false)
		{
			$this->maxHeight	= (intval($height) > $this->currentDimensions['height']) ? $this->currentDimensions['height'] : $height;
			$this->maxWidth		= (intval($width) > $this->currentDimensions['width']) ? $this->currentDimensions['width'] : $width;
		}
		else
		{
			$this->maxHeight	= intval($height);
			$this->maxWidth		= intval($width);
		}
		
		// create the working image
		if (function_exists('imagecreatetruecolor'))
		{
			$this->workingImage = imagecreatetruecolor($this->maxWidth, $this->maxHeight);
		}
		else
		{
			$this->workingImage = imagecreate($this->maxWidth, $this->maxHeight);
		}
		
		$this->preserveAlpha();
		
		$cropWidth	= $this->maxWidth;
		$cropHeight	= $this->maxHeight;
		$cropX 		= 0;
		$cropY 		= 0;
		
		// now, figure out how to crop the rest of the image...
		if ($this->currentDimensions['width'] > $this->maxWidth)
		{
			$cropX = intval(($this->currentDimensions['width'] - $this->maxWidth) / 2);
		}
		elseif ($this->currentDimensions['height'] > $this->maxHeight)
		{
			$cropY = intval(($this->currentDimensions['height'] - $this->maxHeight) / 2);
		}
		
		imagecopyresampled
		(
            $this->workingImage,
            $this->oldImage,
            0,
            0,
            $cropX,
            $cropY,
            $cropWidth,
            $cropHeight,
            $cropWidth,
            $cropHeight
		);
		
		// update all the variables and resources to be correct
		$this->oldImage 					= $this->workingImage;
		$this->currentDimensions['width'] 	= $this->maxWidth;
		$this->currentDimensions['height'] 	= $this->maxHeight;
		
		return $this;
	}
	/**
	 * Calculates new image dimensions, not allowing the width and height to be less than either the max width or height 
	 * 
	 * @param int $width
	 * @param int $height
	 */
	protected function calcImageSizeStrict ($width, $height)
	{
		// first, we need to determine what the longest resize dimension is..
		if ($this->maxWidth >= $this->maxHeight)
		{
			// and determine the longest original dimension
			if ($width > $height)
			{
				$newDimensions = $this->calcHeight($width, $height);
				
				if ($newDimensions['newWidth'] < $this->maxWidth)
				{
					$newDimensions = $this->calcWidth($width, $height);
				}
			}
			elseif ($height >= $width)
			{
				$newDimensions = $this->calcWidth($width, $height);
				
				if ($newDimensions['newHeight'] < $this->maxHeight)
				{
					$newDimensions = $this->calcHeight($width, $height);
				}
			}
		}
		elseif ($this->maxHeight > $this->maxWidth)
		{
			if ($width >= $height)
			{
				$newDimensions = $this->calcWidth($width, $height);
				
				if ($newDimensions['newHeight'] < $this->maxHeight)
				{
					$newDimensions = $this->calcHeight($width, $height);
				}
			}
			elseif ($height > $width)
			{
				$newDimensions = $this->calcHeight($width, $height);
				
				if ($newDimensions['newWidth'] < $this->maxWidth)
				{
					$newDimensions = $this->calcWidth($width, $height);
				}
			}
		}
		
		$this->newDimensions = $newDimensions;
	}
	/**
	 * Preserves the alpha or transparency for PNG and GIF files
	 * 
	 * Alpha / transparency will not be preserved if the appropriate options are set to false.
	 * Also, the GIF transparency is pretty skunky (the results aren't awesome), but it works like a 
	 * champ... that's the nature of GIFs tho, so no huge surprise.
	 * 
	 * This functionality was originally suggested by commenter Aimi (no links / site provided) - Thanks! :)
	 *   
	 */
	protected function preserveAlpha ()
	{
		if ($this->format == 'PNG' && $this->options['preserveAlpha'] === true)
		{
			imagealphablending($this->workingImage, false);
			
			$colorTransparent = imagecolorallocatealpha
			(
				$this->workingImage, 
				$this->options['alphaMaskColor'][0], 
				$this->options['alphaMaskColor'][1], 
				$this->options['alphaMaskColor'][2], 
				0
			);
			
			imagefill($this->workingImage, 0, 0, $colorTransparent);
			imagesavealpha($this->workingImage, true);
		}
		// preserve transparency in GIFs... this is usually pretty rough tho
		if ($this->format == 'GIF' && $this->options['preserveTransparency'] === true)
		{
			$colorTransparent = imagecolorallocate
			(
				$this->workingImage, 
				$this->options['transparencyMaskColor'][0], 
				$this->options['transparencyMaskColor'][1], 
				$this->options['transparencyMaskColor'][2] 
			);
			
			imagecolortransparent($this->workingImage, $colorTransparent);
			imagetruecolortopalette($this->workingImage, true, 256);
		}
	}
}
?>