<?php

class Documentation extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->helper('url_helper');
        $this->load->view('documentation');
    }
}