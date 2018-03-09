<?php

if (!is_logged_in()) {
    redirect('index.php?action=login');
}

$user = $_SESSION['user'];

$data = [
    'title' => 'Add book',
    'defaults' => (isset($_POST)) ? $_POST : [],
    'action' => 'index.php?action=add_book'
];

if ($_POST) {

    $errors = validate_book($_POST);

    if (is_array($errors) && count($errors) > 0) {
        $data['errors'] = $errors;
    } else {
        insert_book($_POST, $user['id']);
        redirect('index.php?action=index');
    }
}

load_template('book/form', $data);
