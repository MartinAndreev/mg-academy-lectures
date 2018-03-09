<?php

$data = [
    'title' => 'Register'
];

if ($_POST) {
    $result = register_user($_POST);

    if (is_array($result)) {
        $data['errors'] = $result;
    } else {
        redirect('index.php?action=login');
    }
}

load_template('register', $data);
