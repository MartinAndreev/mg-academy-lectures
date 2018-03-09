<?php

$data = [
    'title' => 'Login',
    'errors' => []
];

if ($_POST) {

    $user = find_user_by_email($_POST['email']);
    if ($user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user'] = $user;

            redirect('index.php?action=index');
        } else {
            $data['errors'][] = 'Invalid username or password.';
        }
    } else {
        $data['errors'][] = 'Invalid username or password.';
    }
}

load_template('login', $data);
