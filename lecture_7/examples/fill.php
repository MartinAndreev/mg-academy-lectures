<?php

$image = imagecreate(400, 400);
imagecolorallocatealpha($image, 255, 60, 30, 70);

// This wont set the background
imagecolorallocate($image, 255, 255, 255);

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
