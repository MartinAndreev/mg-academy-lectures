<?php

if (!is_logged_in()) {
    redirect('index.php?action=login');
}

$user = $_SESSION['user'];

$data = [
    'title' => 'My books',
    'books' => get_user_books($user['id'])
];

load_template('index', $data);
