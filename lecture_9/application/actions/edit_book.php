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

$data = [
    'title' => 'Edit book',
    'defaults' => (isset($_POST) && count($_POST) > 0) ? $_POST : $book,
    'action' => 'index.php?action=edit_book&id=' . (int) $_GET['id']
];

if ($_POST) {

    $errors = validate_book($_POST);

    if (is_array($errors) && count($errors) > 0) {
        $data['errors'] = $errors;
    } else {
        update_book($_POST, (int) $_GET['id']);
        redirect('index.php?action=index');
    }
}

load_template('book/form', $data);
