<?php

class WebappController extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function home() {
        $this->load->view('webapp/home');
    }

    public function regioni() {
        $this->load->view('webapp/regioni');
    }

    public function province() {
        $this->load->view('webapp/province');
    }

    public function redirect() {
        $this->load->helper('url');
        redirect('webapp/home');
    }
}