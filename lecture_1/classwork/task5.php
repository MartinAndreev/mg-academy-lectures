<?php

$vars = [10, "Some text", "10", 10.5, null, true, [], new stdClass()];

foreach ($vars as $var) {
    echo "Checcking for types <br />";
    var_dump($var);
    echo "<br />";

    echo "Type is " . gettype($var) . "<br />";

    echo "Checking if with is_integer <br />";
    var_dump(is_integer($var));
    echo "<br />";

    echo "Checking if with is_double <br />";
    var_dump(is_double($var));
    echo "<br />";

    echo "Checking if with is_numeric <br />";
    var_dump(is_numeric($var));
    echo "<br />";

    echo "Checking if with is_string <br />";
    var_dump(is_string($var));
    echo "<br />";

    echo "Checking if with is_bool <br />";
    var_dump(is_bool($var));
    echo "<br />";

    echo "Checking if with is_null <br />";
    var_dump(is_null($var));
    echo "<br />";

    echo "Checking if with is_nan <br />";
    var_dump(is_nan($var));
    echo "<br />";

    echo "Checking if with is_finite <br />";
    var_dump(is_finite($var));
    echo "<br />";

    echo "Checking if with is_array <br />";
    var_dump(is_array($var));
    echo "<br />";

    echo "Checking if with is_object <br />";
    var_dump(is_object($var));
    echo "<br />";

    echo "========================================= <br /> <br /> <br /> <br />";
}
