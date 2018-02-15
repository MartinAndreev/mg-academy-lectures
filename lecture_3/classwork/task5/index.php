<?php

$counter = 0;
$sum = 0;

while (true) {
    $tmp = $sum;
    $tmp += $counter + 1;

    if ($tmp > 120) {
        break;
    }

    echo $sum . " + " . $counter . " + 1 = " . $tmp . "<br />";

    $sum = $tmp;
    $counter++;
}

echo $sum;
