<?php

if (!function_exists('load_template')) {

    /**
     * Loads a template file
     * @param string $file
     */
    function load_template($file, $data = []) {
        $path = TEMPLATE_PATH . $file . '.php';

        if (file_exists($path)) {
            include $path;
        }
    }

}

if (!function_exists('load_header')) {

    function load_header($data = [], $file = 'layouts/header') {
        load_template($file, $data);
    }

}


if (!function_exists('load_footer')) {

    function load_footer($data = [], $file = 'layouts/footer') {
        load_template($file, $data);
    }

}

if (!function_exists('is_logged_in')) {

    /**
     * Check if the user is loged in
     * @return type
     */
    function is_logged_in() {
        return (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true);
    }

}

if (!function_exists('redirect')) {

    /**
     * Does a redirect
     * @param url $url
     */
    function redirect($url) {
        header('Location: ' . $url, true, 302);
        die();
    }

}

if (!function_exists('find_user_by_email')) {

    /**
     * Returns the user by email
     * @global PDO $db
     * @param string $email
     * @return boolean|array
     */
    function find_user_by_email($email) {
        global $db;

        $stm = $db->prepare('SELECT * FROM users WHERE email = :email');
        if (!$stm->execute(['email' => $_POST['email']])) {
            return false;
        }

        $row = $stm->fetch(PDO::FETCH_ASSOC);

        return ($row) ? $row : false;
    }

}

if (!function_exists('register_user')) {

    /**
     * Registers a user
     * @global PDO $db
     * @param type $array
     */
    function register_user($array) {
        global $db;

        $errors = [];

        if (!isset($array['name']) || !filter_var(trim($array['name']), FILTER_DEFAULT)) {
            $errors[] = 'Name is required.';
        }

        if (!isset($array['lastname']) || !filter_var(trim($array['lastname']), FILTER_DEFAULT)) {
            $errors[] = 'Lastname is required.';
        }

        if (!isset($array['email']) || !filter_var(trim($array['email']), FILTER_DEFAULT) || !filter_var(trim($array['email']), FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is empty or not valid.';
        }

        if (isset($array['email']) && find_user_by_email($array['email']) !== FALSE) {
            $errors[] = 'Email already exsists.';
        }

        if (!isset($array['password']) || !filter_var(trim($array['password']), FILTER_DEFAULT)) {
            $errors[] = 'Password is required.';
        }

        if (!isset($array['password_confirm']) || !filter_var(trim($array['password_confirm']), FILTER_DEFAULT) || $array['password'] != $array['password_confirm']) {
            $errors[] = 'Confirm password is empty or not equal to password.';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $stm = $db->prepare("INSERT INTO users (name, lastname, email, password, created_on, updated_on) VALUES(:name, :lastname, :email, :password, :created_on, :updated_on)");

        $result = $stm->execute([
            'name' => $array['name'],
            'lastname' => $array['lastname'],
            'password' => password_hash($array['password'], PASSWORD_DEFAULT),
            'email' => $array['email'],
            'created_on' => strtotime('now'),
            'updated_on' => strtotime('now')
        ]);

        return $result;
    }

}

if (!function_exists('get_user_books')) {

    /**
     * 
     * @global PDO $db
     * @param type $userId
     * @param type $perPage
     */
    function get_user_books($userId) {

        global $db;

        $stm = $db->prepare('SELECT * FROM books WHERE user_id = :user_id ORDER BY created_on DESC');
        $stm->execute([
            'user_id' => $userId,
        ]);

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

}

if (!function_exists('get_book_by_id')) {

    /**
     * 
     * @global PDO $db
     * @param type $userId
     * @param type $perPage
     */
    function get_book_by_id($bookId) {

        global $db;

        $stm = $db->prepare('SELECT * FROM books WHERE id = :book_id');
        $stm->execute([
            'book_id' => $bookId,
        ]);

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

}

if (!function_exists('validate_book')) {

    /**
     * Validates the book post
     * @param type $array
     * @return type
     */
    function validate_book($array) {
        $errors = [];

        if (!isset($array['book']) || !filter_var(trim($array['book']), FILTER_DEFAULT)) {
            $errors[] = 'Book is required.';
        }

        if (!isset($array['isbn']) || !filter_var(trim($array['isbn']), FILTER_DEFAULT)) {
            $errors[] = 'Isbn is required.';
        }

        if (!isset($array['author']) || !filter_var(trim($array['author']), FILTER_DEFAULT)) {
            $errors[] = 'Author is required.';
        }

        return (count($errors) > 0) ? $errors : true;
    }

}

if (!function_exists('insert_book')) {

    /**
     * 
     * @global PDO $db
     * @param type $book
     * @param type $userId
     */
    function insert_book($book, $userId) {
        global $db;

        $stm = $db->prepare('INSERT INTO books (book, isbn, user_id, author, created_on, updated_on) VALUES(:book, :isbn, :user_id, :author, :created_on, :updated_on)');

        $result = $stm->execute(array_merge($book, [
            'user_id' => $userId,
            'created_on' => strtotime('now'),
            'updated_on' => strtotime('now')
        ]));

        return $result;
    }

}

if (!function_exists('update_book')) {

    /**
     * 
     * @global PDO $db
     * @param type $book
     * @param type $userId
     */
    function update_book($book, $bookId) {
        global $db;

        $stm = $db->prepare('UPDATE books SET book = :book, isbn = :isbn, author = :author, updated_on = :updated_on WHERE id = :book_id');

        $result = $stm->execute(array_merge($book, [
            'book_id' => $bookId,
            'updated_on' => strtotime('now')
        ]));

        return $result;
    }

}

if (!function_exists('delete_book')) {

    /**
     * 
     * @global PDO $db
     * @param type $book
     * @param type $userId
     */
    function delete_book($bookId) {
        global $db;

        $stm = $db->prepare('DELETE FROM books WHERE id = :book_id');

        $result = $stm->execute([
            'book_id' => $bookId,
        ]);

        return $result;
    }

}