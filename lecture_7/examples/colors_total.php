<?php

define('TEST_IMAGES_DIR', dirname(__FILE__) . '/test_images/');

function convert($size)
{
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i    = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

echo "============================= PNG ========================== <br />";
$png = imagecreatefrompng(TEST_IMAGES_DIR . 'png_test_image.png');

echo "Total colors: ", imagecolorstotal($png), "<br />";
echo "Size: width ", imagesx($png), " in px. height: ", imagesy($png), " in px <br />";
echo "Ram consumption:" . convert(memory_get_peak_usage()) . " <br />";

imagedestroy($png);

echo "<br /> ============================= JPG ========================== <br />";
$jpg = imagecreatefromjpeg(TEST_IMAGES_DIR . 'jpg_test_image.jpg');
echo "Total colors: ", imagecolorstotal($jpg), "<br />";
echo "Size: width ", imagesx($jpg), " in px. height: ", imagesy($jpg), " in px <br />";
echo "Ram consumption:" . convert(memory_get_peak_usage()) . " <br />";

imagedestroy($jpg);

echo "<br /> ============================= GIF ========================== <br />";
$gif = imagecreatefromgif(TEST_IMAGES_DIR . 'gif_test_image.gif');
echo "Total colors: ", imagecolorstotal($gif), "<br />";
echo "Size: width ", imagesx($gif), " in px. height: ", imagesy($gif), " in px <br />";
echo "Ram consumption:" . convert(memory_get_peak_usage()) . " <br />";

imagedestroy($gif);