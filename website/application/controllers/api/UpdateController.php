<?php

class UpdateController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library('json');
    }

    public function index() { 
        $this->load->model('regions');
        $this->load->model('provinces');

        // connect to database
        $this->load->database();

        // lock and transactin
        $this->db->query("LOCK TABLES covid_regional_trend WRITE, covid_provincial_trend WRITE");
        $this->db->trans_start();

        if($this->db->error()['code'] !== 0)
            $this->json->error(1, "Database error");

        // command
        if(!$this->regions->update_regions()) 
            $this->json->error(2, "Database error");

        if(!$this->provinces->update_provinces())
            $this->json->error(3, "Database error");

        // lock and transaction
        $this->db->trans_complete();
        $this->db->query("UNLOCK TABLES");

        if($this->db->error()['code'] !== 0)
            $this->json->error(3, "Database error");

        $this->json->success(array('success' => true));
    }
    
}