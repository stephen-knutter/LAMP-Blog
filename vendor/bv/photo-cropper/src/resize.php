<?php
	namespace bv;
	
	Class resize {
	
		private $image;
		private $width;
		private $height;
		private $imageResized;
		
		public function __construct(/*$tmp_file,*/ $file_name){
			/*OPEN IMAGE*/
			$this->image = $this->openImage(/*$tmp_file,*/ $file_name);
			
			/*GET DIMENSIONS*/
			$this->width = /*$tmpWidth;*/ imagesx($this->image);
			$this->height = /*$tmpHeight;*/ imagesy($this->image);
		}
		
		public function resizeImage($newWidth, $newHeight, $option="auto", $x=0, $y=0, $w=0, $h=0, $mes=true){
			if($mes == true){
				$ratio = round($this->width,0) / round($this->height,0);
				if( $ratio == 1){
					$option = 'exact';
				} elseif ( $ratio > 1){
					$option = 'portrait';
				} elseif ( $ratio < 1 ){
					$option = 'landscape';
				}  else {
					$option = 'exact';
				}
			}
			
			$optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
			
			$optimalWidth = $optionArray['optimalWidth'];
			$optimalHeight = $optionArray['optimalHeight'];
			
			if($mes == true || $option == 'portrait' || $option == 'landscape'){
				$src_width = $this->width;
				$src_height = $this->height;
			} else {
				$src_width = $w;
				$src_height = $h;
			}
			$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
			$whiteBack = imagecolorallocate($this->imageResized, 255, 255, 255);
			imagefill($this->imageResized, 0, 0, $whiteBack);
			imagecopyresampled($this->imageResized, $this->image, 0, 0, $x, $y, $optimalWidth, $optimalHeight, /*$this->width*/$src_width, /*$this->height*/$src_height);
			
			
			if($option == 'crop'){
				$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
			}
		}
		
		public function saveImage($savePath, $imageQuality="100"){
			$extension = strrchr($savePath, '.');
			$extension = strtolower($extension);
			
			switch($extension){
				case '.jpg':
				case '.jpeg':
					if(imagetypes() & IMG_JPG){
						imagejpeg($this->imageResized, $savePath, $imageQuality);
					}
					break;
				case '.gif':
					if(imagetypes() & IMG_GIF){
						imagegif($this->imageResized, $savePath);
					}
					break;
				case '.png':
					/*Image quality for PNG is 0-9*/
					$scaleQuality = round(($imageQuality/100) * 9);
					$invertScaleQuality = 9 - $scaleQuality;
					
					if(imagetypes() & IMG_PNG){
						imagepng($this->imageResized, $savePath, $invertScaleQuality);
					}
					break;
				default:
					//No Extension
					break;
			}
			imagedestroy($this->imageResized);
		}
		
		
		private function getDimensions($newWidth, $newHeight, $option){
			switch($option){
				case 'exact':
					$optimalWidth = $newWidth;
					$optimalHeight = $newHeight;
				break;
				case 'portrait':
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight = $newHeight;
				break;
				case 'landscape':
					$optimalWidth = $newWidth;
					$optimalHeight = $this->getSizeByFixedWidth($newWidth);
				break;
				case 'auto':
					$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
					$optimalWidth = $optionArray['optimalWidth'];
					$optimalHeight = $optionArray['optimalHeight'];
				break;
				case 'crop':
					$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
					$optimalWidth = $optionArray['optimalWidth'];
					$optimalHeight = $optionArray['optimalHeight'];
				break;
			}
			return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
		}
		
		private function getSizeByFixedHeight($newHeight){
			$ratio = $this->width / $this->height;
			$newWidth = $newHeight * $ratio;
			return $newWidth;
		}
		
		private function getSizeByFixedWidth($newWidth){
			$ratio = $this->height / $this->width;
			$newHeight = $newWidth * $ratio;
			return $newHeight;
		}
		
		private function getSizeByAuto($newWidth, $newHeight){
			if($this->height < $this->width){
				/*RESIZE AS LANDSCAPE*/
				$optimalWidth = $newWidth;
				$optimalHeight = $this->getSizeByFixedWidth($newWidth);
			} elseif($this->height > $this->width) {
				/*RESIZE AS PORTRAIT*/
				$optimalWidth = $this->getSizeByFixedHeight($newHeight);
				$optimalHeight = $newHeight;
			} else {
				/*RESIZE AS A SQUARE*/
				if($newHeight < $newWidth){
					$optimalWidth = $newWidth;
					$optimalHeight = $this->getSizeByFixedWidth($newWidth);
				} elseif ($newHeight > $newWidth){
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight = $newHeight;
				} else {
					$optimalWidth = $newWidth;
					$optimalHeight = $newHeight;
				}
			}
			return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
		}
		
		private function getOptimalCrop($newWidth, $newHeight){
			$heightRatio = $this->height / $newHeight;
			$widthRatio = $this->width / $newWidth;
			
			if($heightRatio < $widthRatio){
				$optimalRatio = $heightRatio;
			} else {
				$optimalRatio = $widthRatio;
			}
			
			$optimalHeight = $this->height / $optimalRatio;
			$optimalWidth = $this->width / $optimalRatio;
			
			return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
		}
		
		private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight){
			$cropStartX = ($optimalWidth / 2) - ($newWidth / 2);
			$cropStartY = ($optimalHeight / 2) - ($newHeight / 2);
			
			$crop = $this->imageResized;
			
			$this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
			$whiteBack = imagecolorallocate($this->imageResized, 255, 255, 255);
			imagefill($this->imageResized, 0, 0, $whiteBack);
			imagecopyresampled($this->imageResized, $crop, 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight, $newWidth, $newHeight);
		}
		
		private function openImage(/*$tmp_file,*/ $file_name){
			$extension = strtolower(strrchr($file_name, '.'));
		
			switch($extension){
				case '.jpg':
				case '.jpeg':
					$img = @imagecreatefromjpeg(/*$tmp_file*/ $file_name);
					break;
				case '.gif':
					$img = @imagecreatefromgif(/*$tmp_file*/ $file_name);
					break;
				case '.png':
					$img = @imagecreatefrompng(/*$tmp_file*/ $file_name);
					break;
				default:
					$img = false;
					break;
			}
			return $img;
		}
	}
