<?php

define('IMAGE_PATH', dirname(__FILE__) . '/images/');
define('THUMB_PATH', dirname(__FILE__) . '/thumbs/');

ini_set('display_errors', 'on');

if(!is_dir(THUMB_PATH)) {
    mkdir(THUMB_PATH, 0755, true);
}

/**
 * Detects the image type and returns a new resource based on the type
 * @param type $path
 * @return type
 */
function detect_create($path) {
    $type = getimagesize($path);
    
    $image = null;
    switch ($type['mime']) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($path);
            break;
        case 'image/png':
            $image = imagecreatefrompng($path);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($path);
            break;
    }
    
    return $image;
}

/**
 * Saves the image to a given destination
 * @param type $image The image resource object
 * @param type $path The save directory
 * @param type $name The filename
 * @param type $with The width
 * @param type $height The height
 * @return string Returns the path to the file
 */
function detect_save($image, $name, $with, $height) {
    $path = THUMB_PATH;
    $type = getimagesize($name);
    
    $pathinfo = pathinfo($name);
    
    $newName = $path . $pathinfo['filename'] . '-' . $with .'x' . $height . '.' . $pathinfo['extension'];
    
    switch ($type['mime']) {
        case 'image/jpeg':
            imagejpeg($image, $newName);
            break;
        case 'image/png':
            imagepng($image, $newName);
            break;
        case 'image/gif':
            imagegif($image, $newName);
            break;
    }
    
    imagedestroy($image);
    
    return $newName;
}

function resize($file, $width, $height) {
    
    if(!file_exists($file)) {
        return false;
    }
    
    $image = detect_create($file);
    
    $oldWith = imagesx($image);
    $oldHeight = imagesy($image);
    
    $tmp = imagecreatetruecolor($width, $height);
    
    imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $oldWith, $oldHeight);
    
    imagedestroy($image);
    
    return detect_save($tmp, $file, $width, $height);
}

$path = resize(IMAGE_PATH . 'car2.jpg', 500, 300);

if($path) {
    $image = detect_create($path);
    $type = getimagesize($path);
    
    header('Content-type:' . $type['mime']);
    
    switch ($type['mime']) {
        case 'image/jpeg':
            imagejpeg($image);
            break;
        case 'image/png':
            imagepng($image);
            break;
        case 'image/gif':
            imagegif($image);
            break;
    }
    
    imagedestroy($image);
}
