<?php

$directory = dirname(__FILE__) . '/images/';
$file = 'car2.jpg';

function resize_crop($file, $width, $height) {
    
    $tmpImage = imagecreatetruecolor($width, $height);
    $size = imagesx($file);
    
    $ratioX = 
    
}

function create_image_resource($image) {
    
    if(!file_exists($image)) {
        return false;
    }
    $type = getimagesize($image);
    
    
    
}