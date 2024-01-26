<?php

namespace app\src;

class Image
{
  const IMG_PATH = 'assets/imgs/photos/';
  protected $filename;

  public function getName()
  {
    return $this->filename;
  }

  public function upload($imageName, $resizeW)
  {
    $tmpname = $_FILES[$imageName]['tmp_name'];
    $imgSize = getimagesize($tmpname);

    $extension = substr($imgSize['mime'], strrpos($imgSize['mime'], '/') + 1);
    if($extension == 'jpeg') {
      $extension = 'jpg';
    }
    if($extension == 'jpg') {
      $image = imagecreatefromjpeg($tmpname);
    }
    else if($extension == 'png') {
      $image = imagecreatefrompng($tmpname);
    }
    else { // $extension == 'gif'
      $image = imagecreatefromgif($tmpname);
    }
    $this->filename = md5(uniqid()).time().'.'.$extension;

    $imgW = $imgSize[0];
    $imgH = $imgSize[1];
    $ratio = $resizeW / $imgW;
    $resizeH = round($imgH * $ratio);

    $resizedImage = imagecreatetruecolor($resizeW, $resizeH);
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $resizeW, $resizeH, $imgW, $imgH);

    if($extension == 'jpg') {
      imagejpeg($resizedImage, self::IMG_PATH.$this->filename, 90);
    }
    else if($extension == 'png') {
      imagepng($resizedImage, self::IMG_PATH.$this->filename);
    }
    else { // $extension == 'gif'
      imagegif($resizedImage, self::IMG_PATH.$this->filename);
    }
  }
}
