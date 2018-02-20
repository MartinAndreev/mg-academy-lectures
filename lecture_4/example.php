<?php

//$en = 'Hello';
//$bg = 'Здравей.';
//var_dump(strlen($en));
//var_dump(strlen($bg));
//
//var_dump(mb_strlen($en));
//var_dump(mb_strlen($bg));
//$string = 'Hello world';
//
//$result = strpos($string, 'Hello'); 
//
//if(!$result) {
//   echo 'Not found <br />';
//}
//
//if($result !== FALSE) {
//    echo 'Found at ', $result;
//}
//$string = 'My awesome string!';
//
//echo substr($string, -6, 1);
//
//$html = '<h1>My awesome header</h1>';
//echo str_replace(['<h1>', '</h1>'], ['<h2>', '</h2>'], $html);
//$string = 'My string is lame.';
//
//$exploded = explode(' ', $string);


/* $where = [];

  $where[] = 'user_id = 10';
  $where[] = 'is_active = 1';

  $whereOr = [];
  $whereOr[] = 'rank = "a"';
  $whereOr[] = 'username = "marto"';

  $where[] = '(' . implode(' OR ', $whereOr) . ')';

  $sql = 'SELECT * FROM users WHERE ' . implode(' AND ', $where);

  echo $sql; */
//
date_default_timezone_set('Europe/Sofia');
echo date('d.m.Y H:i:s');

echo date('d.m.Y', strtotime('+ 3 months'));