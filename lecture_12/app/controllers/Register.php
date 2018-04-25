<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends App_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {

        if ($this->input->post()) {
            $post = $this->input->post();
            $user = new App\Models\User();

            foreach ($post as $key => $data) {
                $user->{$key} = $data;
            }
            
            $user->setPassword($this->input->post('password'));

            if ($user->save()) {
                redirect('login');
            } else {
                $this->twiggy->set('form_data', $this->input->post());
                $this->twiggy->set('errors', $user->getErrors());
            }
        }

        $this->twiggy->template('common/register')->display();
    }

    protected function _needsAuth(): bool {
        return false;
    }

}
