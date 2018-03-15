<?php

class User {

    protected $id;
    protected $password;
    public $email;
    public $name;
    public $lastname;
    public $created_on;
    public $updated_on;
    protected $_errors = [];

    /**
     * Checks if the user is logged in
     * @return boolean
     */
    static function isLogged() {
        return (isset($_SESSION['user']) && unserialize($_SESSION['user']) instanceof User);
    }

    public function load($id) {
        $this->_user = Registry::instance()->getDB()->query('SELECT * FROM users WHERE id = :id', [
                    'id' => $id
                ])->fetch();
    }

    public function save() {
        $this->validate();

        if (count($this->_errors) > 0) {
            return false;
        }

        if ($this->id) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    protected function insert() {
        
    }

    protected function update() {
        
    }

    protected function validate() {
        
    }

    public function errors() {
        return $this->_errors;
    }

    static function get_by_email($email) {
        
    }

}
