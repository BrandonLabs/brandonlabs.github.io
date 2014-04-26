<?php

$cacheFolder = 'cache';

ini_set("display_errors",FALSE);
header("Content-type: image/png");

$username = $_GET['username'];
$size = $_GET['size'] > 0 ? $_GET['size'] : 100;

if(isset($_GET['cache'])){
  if(!is_dir($cacheFolder)){
    mkdir($cacheFolder);
  }
  
  $cachePath = $cacheFolder . DIRECTORY_SEPARATOR . $username . '.png';

  if(is_file($cachePath) && !isset($_GET['skip'])){
    include($cachePath);
    exit();
  }
}

$src = imagecreatefrompng("http://minecraft.net/skin/{$username}.png");

if(!$src){
  $src = imagecreatefrompng("http://www.minecraft.net/skin/char.png");
}

$dest   = imagecreatetruecolor(8, 8);
imagecopy($dest, $src, 0, 0, 8, 8, 8, 8);   

$bg_color = imagecolorat($src, 0, 0);
$no_helm  = true;

for ($i = 1; $i <= 8; $i++) {
  for ($j = 1; $j <= 4; $j++) {

    if (imagecolorat($src, 40 + $i, 7 + $j) != $bg_color) {
      $no_helm = false;
    }
  }

  if (!$no_helm)
    break;
}

if (!$no_helm) {
  imagecopy($dest, $src, 0, -1, 40, 7, 8, 4);
}
$final = imagecreatetruecolor($size, $size);
imagecopyresized($final, $dest, 0, 0, 0, 0, $size, $size, 8, 8);

if(isset($_GET['cache'])){
  imagepng($final, $cachePath);
  include($cachePath);
}
else {
  imagepng($final);
}
imagedestroy($im);
imagedestroy($dest);
imagedestroy($final);

?>
