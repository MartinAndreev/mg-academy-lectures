<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends App_Controller {
    
    public function index() {
        $this->twiggy->template('dashboard')->display();
    }

    public function test() {
        $this->twiggy->template('test')->display();
    }

    protected function _needsAuth(): bool {
        return true;
    }

}
