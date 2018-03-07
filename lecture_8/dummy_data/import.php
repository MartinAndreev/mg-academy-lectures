<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$db = new PDO('mysql:host=localhost;dbname=classwork_8;charset=utf8', 'root', base64_decode('MWNlZnJlYWs='));

$orderStatuses = ['paid', 'not_paid', 'processing', 'canclled'];
$hosts = ['@example.com', '@post.com', '@php.net', '@php-net.com', '@exam.com', '@mg.org'];

$customerTable = "CREATE TABLE IF NOT EXISTS `customers` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(120) NOT NULL,
 `lastname` varchar(120) NOT NULL,
 `email` varchar(160) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$ordersTable = "CREATE TABLE `orders` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `customer_id` int(10) unsigned NOT NULL,
 `order_total` decimal(10,2) NOT NULL,
 `order_status` enum('paid','not_paid','processing','canclled') NOT NULL,
 `order_date` datetime NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";

$db->query($customerTable);
$db->query($ordersTable);

$db->query('TRUNCATE TABLE customers');
$db->query('TRUNCATE TABLE orders');

mt_srand(time() * mt_rand(0, 999999));

for($i = 0; $i < 100; $i++) {
    
    $statement = $db->prepare('INSERT INTO customers (name, lastname, email) VALUES(?, ?, ?)');
    
    $host = array_rand($hosts);
    
    $statement->execute([
        generateRandomString(10), generateRandomString(5), generateRandomString(12) . $hosts[$host]
    ]);
    
}

for($i = 0; $i < 1000; $i++) {
    
    $statement = $db->prepare('INSERT INTO orders (customer_id, order_total, order_status, order_date) VALUES(?, ?, ?, ?)');
    
    $customer_id = mt_rand(0, 1000);
    $total = mt_rand(0, 60000);
    $status = array_rand($orderStatuses);
    
    $day = mt_rand(1, 20);
    
    $statement->execute([
        $customer_id, $total, $orderStatuses[$status], date('Y-m-' . $day . ' H:i:s')
    ]);
    
}