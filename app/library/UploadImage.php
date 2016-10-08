<?php
class UploadImage {

   var $image;
   var $image_type;

   function load($filename,$model) {
      @mkdir("img/".date("Y")."/".$model, 0777, true);
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }

   // get the image type
   function FileExtension($file){
    if($file=='image/gif'){$extension=".gif";}
    if($file=='image/pjpeg'){$extension=".jpg";}
    if($file=='image/jpeg'){$extension=".jpg";}
    if($file=='image/bmp'){$extension=".bmp";}
    if($file=='image/x-png'){$extension=".png";}
    if($file=='image/png'){$extension=".png";}
    return $extension;
   }

   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null) {
		// do this or they'll all go to jpeg
		$image_type=$this->image_type;
		if( $image_type == IMAGETYPE_JPEG ) {
		 imagejpeg($this->image,$filename,$compression);
		} elseif( $image_type == IMAGETYPE_GIF ) {
		 imagegif($this->image,$filename);
		} elseif( $image_type == IMAGETYPE_PNG ) {
		// need this for transparent png to work
		imagealphablending($this->image, false);
		imagesavealpha($this->image,true);
		imagepng($this->image,$filename);
		}
		if( $permissions != null) {
		 chmod($filename,$permissions);
		}
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
   function resize($width,$height,$forcesize='n') {
	/* optional. if file is smaller, do not resize. */
	if ($forcesize == 'n') {
	  if ($width > $this->getWidth() && $height > $this->getHeight()){
		  $width = $this->getWidth();
		  $height = $this->getHeight();
	  }
	}
	$new_image = imagecreatetruecolor($width, $height);
	/* Check if this image is PNG or GIF, then set if Transparent*/
	if(($this->image_type == IMAGETYPE_GIF) || ($this->image_type==IMAGETYPE_PNG)){
	  imagealphablending($new_image, false);
	  imagesavealpha($new_image,true);
	  $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
	  imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);
	}
	imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());

	$this->image = $new_image;
   }
}
//////////////.............................///////////////////
function watermarkImage ($SourceFile, $WaterMarkText, $DestinationFile, $Type) {
   list($width, $height) = getimagesize($SourceFile);
   $image_p = imagecreatetruecolor($width, $height);
   //.........//
	$Type = strtolower($Type);
      if( $Type == ".jpeg" || $Type == ".jpg") {
	   $image = imagecreatefromjpeg($SourceFile);
      } elseif( $Type == ".gif" ) {
 	   $image = imagecreatefromgif($SourceFile);
      } elseif( $Type == ".png" ) {
	   $image = imagecreatefrompng($SourceFile);
      }
    //.........//
   imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
   $black = imagecolorallocate($image_p, 0, 0, 0);
   $grey= imagecolorallocate($image_p, 240, 240, 240);
   $font = 'images/GECKO.TTF';
   $font_size = $width/20;
   imagettftext($image_p, $font_size, 0, $width/5, $height-10, $grey, $font, $WaterMarkText);
   if ($DestinationFile<>'') {
      imagejpeg ($image_p, $DestinationFile, 100);
   } else {
      header('Content-Type: image/jpeg');
      imagejpeg($image_p, null, 100);
   };
   imagedestroy($image);
   imagedestroy($image_p);
};
?>
