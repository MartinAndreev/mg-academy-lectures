<?php

abstract class App_Controller extends CI_Controller {

    /**
     * Checks if the controllers needs a loged in user
     */
    protected abstract function _needsAuth(): bool;

    protected function _checkAuth() {
        if (!\App\Models\User::isLoogedIn()) {
            redirect('login');
        }
    }

    public function __construct() {
        parent::__construct();

        if ($this->_needsAuth()) {
            $this->_checkAuth();
        }

        $this->twiggy->set('is_logged_in', \App\Models\User::isLoogedIn());

        if (\App\Models\User::isLoogedIn()) {
            $this->twiggy->set('user', \App\Models\User::getLoggedUser());

            $current = App\Models\Contragent::getCurrent();
            if (
                    !$current && $this->router->class != 'suppliers' && $this->router->method != 'add'
            ) {
                redirect('suppliers/add');
            }

            $this->twiggy->set('suppliers', App\Models\Contragent::getSuppliers());
            $this->twiggy->set('current_supplier', $current);
        }
    }

}
