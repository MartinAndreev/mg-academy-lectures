<?php

$image = imagecreatetruecolor(500, 500);

$black = imagecolorallocate($im, 0, 0, 0);
$red   = imagecolorallocate($image, 255, 0, 0);
$blue  = imagecolorallocate($image, 0, 0, 255);

// Make the black transparent
imagecolortransparent($image, $black);

imagerectangle($image, 20, 20, 480, 480, $red);
imagefilledrectangle($image, 40, 40, 460, 460, $blue);
header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
