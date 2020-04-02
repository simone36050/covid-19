<?php

class AreaController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library('json');
        $this->load->library('params');
    }

    public function index() {
        $this->load->model('area');

        // read input
        try {
            $only_region = $this->params->flag('only_region');
            $regions = $this->params->list_int('regions', true, null);
        } catch (ParamException $e) {
            $this->json->error(1, "Wrong input");
            return;
        }

        // open database connection
        $this->load->database();
        $out = null;

        // only region list
        if($only_region) 
            $out = $this->area->list_only_region($regions);

        // regions and provinces
        else 
            $out = $this->area->list_regions_provinces($regions);

        if($out !== null)
                $this->json->success($out);
            else
                $this->json->error(2, "Database error");
    }
}
