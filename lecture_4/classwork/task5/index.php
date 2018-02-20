<?php

$string = '<strong>asdasdasdas</strong> html <span>string</span> is really <em>interesting</em>';


$count = strlen($string);
$current = 0;

$found = strpos($string, '<strong>', 0);
if($found !== FALSE) {
    
    $end = strpos($string, '</strong>', $found);
    var_dump($end, $found);
    
    echo substr($string, ($found + 8), ($end - 8));
    
}