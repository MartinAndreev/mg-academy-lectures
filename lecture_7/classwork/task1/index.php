<?php

function generateRect($width, $height, $color = []) {
    
    $image = imagecreatetruecolor($width + 20, $height + 20);
    $black = imagecolorallocate($image, 0, 0, 0);
    imagecolortransparent($image, $black);
    
    list($red, $green, $blue) = $color;
    
    imagefilledrectangle($image, 10, 10, $width + 10, $height + 10, imagecolorallocate($image, $red, $green, $blue));
    
    header('Content-type: image/png');
    imagepng($image);
    
    imagedestroy($image);
}

generateRect(400, 500, [250, 230,80]);