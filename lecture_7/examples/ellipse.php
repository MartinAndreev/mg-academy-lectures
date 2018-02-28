<?php

// Create a blank image.
$image       = imagecreatetruecolor(400, 300);
// Select the background color.
$bg          = imagecolorallocate($image, 0, 0, 0);
// Fill the background with the color selected above.
imagefill($image, 0, 0, $bg);
// Choose a color for the ellipse.
$col_ellipse = imagecolorallocate($image, 255, 255, 255);
// Draw the ellipse.
imageellipse($image, 200, 150, 300, 200, $col_ellipse);
// Output the image.
header("Content-type: image/png");
imagepng($image);
