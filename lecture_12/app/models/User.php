<?php

namespace App\Models;

class User extends \App\Models\Core\Default_Object_Model {

    protected $_protected = ['password'];

    /**
     * Should is hash the password
     * @var type 
     */
    protected $_hashPassword = false;

    public function getRules(): array {
        return [
            [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required'
            ],
            [
                'field' => 'lastname',
                'label' => 'lastname',
                'rules' => 'required'
            ],
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|valid_email'
            ],
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            ]
        ];
    }

    public function getPrimaryKey() {
        return 'id';
    }

    public function getTable(): string {
        return 'users';
    }

    /**
     * Used to set the password
     * @param string $password
     */
    public function setPassword(string $password) {
        $this->_data['password'] = $password;
        $this->_hashPassword = true;
    }

    public function validatePassword($password) {
        return password_verify($password, $this->_data['password']);
    }

    public function createSession() {
        $this->session->set_userdata([
            'is_logged_in' => true,
            'user' => serialize($this),
            'ip_addres' => $this->input->ip_address(),
        ]);

        return true;
    }

    public function destroySession() {
        $this->session->unset_userdata('is_logged_in');
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('ip_addres');
        $this->session->unset_userdata('current_supplier');
        
        return true;
    }

    public function beforeValidation() {
        if ($this->_hashPassword && $this->password != $this->get('password-confirm')) {
            $this->_errors[] = 'The passwords do not match.';
        }
    }

    protected function beforeSave() {
        if ($this->_hashPassword) {
            $this->_data['password'] = password_hash($this->password, PASSWORD_DEFAULT);
        }

        $this->updated_on = strtotime('now');
    }

    protected function beforeCreate() {
        $this->created_on = strtotime('now');
    }

    /**
     * Checks the logged in user
     * @return User
     */
    static function getLoggedUser() {
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        
        if ($user && unserialize($user) instanceof \App\Models\User) {
            return unserialize($user);
        } else {
            return false;
        }
    }

    /**
     * Check if the user is logged in
     * @return bool
     */
    static function isLoogedIn(): bool {
        $ci = & get_instance();

        return ($ci->session->userdata('is_logged_in') && self::getLoggedUser());
    }
    
    function getFullname() {
        return $this->_data['name'] . ' ' . $this->_data['lastname'];
    }

}
