<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;

class UploadHelper {

  const POST_IMAGE = "uploads/posts_images";
  const DEFAULT_IMAGE = "images/otter.jpg";

  private string $publicPath;
  public function __construct(string $publicPath)
  {
    $this->publicPath = $publicPath;
  }
  
  /**
   * @param UploadedFile $file
   * @return string
   */
  public function uploadPostImage($file)
  {
    $destination = $this->publicPath .'/public/'. self::POST_IMAGE;
    $orifinalFilename = $file->getClientOriginalName();
    $baseFileName = pathinfo($orifinalFilename, PATHINFO_FILENAME);
    $fileName = Urlizer::urlize($baseFileName) . '-' . uniqid() . '.' . $file->guessExtension();
    $file->move($destination, $fileName);
    
    return $fileName;
  }
}