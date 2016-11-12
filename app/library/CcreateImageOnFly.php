<?php
namespace app\library;
class CreateImageOnFly {


  static function CreateImage($file,$reference_keys,$model) {
    chdir(dirname(__FILE__));

    //Get the base url
    $url=\Phalcon\Di::getDefault()->getShared('url');
    $url=$url->getBaseUri();
    // img folder path
    $public_folder="../../";
    // model folder paths
    $model_path=$public_folder."public/img/".date("Y")."/".$model;

    if( !file_exists($public_folder.$file) ) {
      return false;
    }
    // create the folder
    if (!is_dir($model_path."/cashe")){@mkdir($model_path."/cashe", 0777);}
    if (!is_dir($model_path."/cashe".$reference_keys)){@mkdir($model_path."/cashe/".$reference_keys, 0777);}
    // original file path
    $original =$public_folder.$file;
    // Get the file name only
    $file_name=@explode("/",$file);
    //cache folder path
    $cache_file ="public/img/".date("Y")."/".$model."/cashe/".$reference_keys."/".$file_name[4];
    $target = $public_folder.$cache_file;
    $new_img_path= $url.$cache_file;
    // Check the size is valid
    switch ($reference_keys) {
      case 'profile':
        $thumbWidth = 200;
        $thumbHeight = 200;
        break;

      case 'icon':
        $thumbWidth = 55;
        $thumbHeight = 55;
        break;

      case 'view':
        $thumbWidth = 185;
        $thumbHeight = null;
        break;

      case 'banner':
        $thumbWidth = 500;
        $thumbHeight = null;
        break;

      default:
        $thumbWidth = 100;
        $thumbHeight = 100;
    }

    // Check the original file exists
    if (!is_file($original)) {
      //die('File doesn\'t exist');
    }

    // Make sure the directory exists
    if (!is_dir($reference_keys)) {
      @mkdir($reference_keys);
      if (!is_dir($reference_keys)) {
        //die('Cannot create directory');
      }
      @chmod($reference_keys, 0777);
    }

    // Make sure the file doesn't exist already
    if (!file_exists($target)) {

      // Make sure we have enough memory
      ini_set('memory_limit', 128*1024*1024);

      // Get the current size & file type
      list($width, $height, $type) = @getimagesize($original);

      // Load the image
      switch ($type) {
        case IMAGETYPE_GIF:
          $image = @imagecreatefromgif($original);
          break;

        case IMAGETYPE_JPEG:
          $image = @imagecreatefromjpeg($original);
          break;

        case IMAGETYPE_PNG:
          $image = @imagecreatefrompng($original);
          break;
      }

      // Calculate height automatically if not given
      if ($thumbHeight === null) {
        $thumbHeight = round($height * $thumbWidth / $width);
      }

      // Ratio to resize by
      $widthProportion = $thumbWidth / $width;
      $heightProportion = $thumbHeight / $height;
      $proportion = max($widthProportion, $heightProportion);

      // Area of original image that will be used
      $origWidth = floor($thumbWidth / $proportion);
      $origHeight = floor($thumbHeight / $proportion);

      // Co-ordinates of original image to use
      $x1 = floor($width - $origWidth) / 2;
      $y1 = floor($height - $origHeight) / 2;

      // Resize the image
      $thumbImage = @imagecreatetruecolor($thumbWidth, $thumbHeight);
      @imagecopyresampled($thumbImage, $image, 0, 0, $x1, $y1, $thumbWidth, $thumbHeight, $origWidth, $origHeight);

      // Save the new image
      switch ($type)
      {
        case IMAGETYPE_GIF:
          @imagegif($thumbImage, $target);
          break;

        case IMAGETYPE_JPEG:
          @imagejpeg($thumbImage, $target, 90);
          break;

        case IMAGETYPE_PNG:
          @imagepng($thumbImage, $target);
          break;

        default:
          throw new LogicException;
      }

      // Make sure it's writable
      chmod($target, 0666);

      // Close the files
      @imagedestroy($image);
      @imagedestroy($thumbImage);
    }

    return $new_img_path;
  }
};
?>
