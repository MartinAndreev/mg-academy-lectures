<?php

if (!is_logged_in()) {
    redirect('index.php?action=login');
}

$user = $_SESSION['user'];

if (!isset($_GET['id']) || trim($_GET['id']) == '') {
    redirect('index.php?action=index');
}

$book = get_book_by_id((int) $_GET['id']);

if (!$book || $book['user_id'] != $user['id']) {
    redirect('index.php?action=index');
}

delete_book((int) $_GET['id']);
redirect('index.php?action=index');

