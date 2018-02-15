<?php

if ($_POST) {
    $message = '';

    if (!isset($_POST['email']) || $_POST['email'] == '') {
        $message = $message . '<p>Enter an email.</p>';
    }

    if (!isset($_POST['phone']) || (int) $_POST['phone'] == 0) {
        $message = $message . '<p>Your phone is not valid.</p>';
    }

    if (!isset($_POST['terms']) || $_POST['terms'] != 1) {
        $message = $message . '<p>Please, accsept the temrs and conditions.</p>';
    }

    if ($_POST['password'] != $_POST['password-confirm']) {
        $message = $message . '<p>Your passwords do not match.</p>';
    }
}