<?php

$sum = 0;

for ($i = 0; $i <= 100; $i++) {

    if ($i % 2 != 0) {
        continue;
    }

    $sum += $i;
}

echo $sum;
