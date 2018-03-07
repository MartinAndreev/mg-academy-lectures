<?php

ini_set('display_errors', 'on');
function generateBulgarianFlag($width, $height) {
    
    $image = imagecreatetruecolor($width, $height);
    $segmentHeight = $height / 3;
    
    $white = imagecolorallocate($image, 255, 255, 255);
    $green = imagecolorallocate($image, 0, 255, 0);
    $red = imagecolorallocate($image, 255, 0, 0);
    
    $segments = [$white, $green, $red];
    
    foreach ($segments as $key => $segment) {
        imagefilledrectangle($image, 0, $segmentHeight * $key, $width, ($key + 1) * $segmentHeight, $segment);
    }
    
    header('Content-type: image/png');
    imagepng($image);
    
    imagedestroy($image);
    
}

generateBulgarianFlag(300, 120);