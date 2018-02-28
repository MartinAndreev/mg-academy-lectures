<?php

// Create a blank image
$image    = imagecreatetruecolor(400, 300);
// Allocate a color for the polygon
$col_poly = imagecolorallocate($image, 255, 255, 255);
// Draw the polygon
imagepolygon($image, array(
    0, 0,
    100, 200,
    300, 200
        ), 3, $col_poly);
// Output the picture to the browser
header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
