<?php

class RegionsController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library('json');
        $this->load->library('params');
    }

    public function index() {
        $this->load->model('regions');
        
        $modes = array('absolute', 'relative');
        $groups = array('region', 'date');
        $datas = array('icu', 'hospitalized', 'isolation', 'positives', 'cures', 'dead', 'swabs');

        // read input
        try {
            $mode = $this->params->choice('mode', $modes, true, 'absolute');
            $group = $this->params->choice('group', $groups, true, 'region');
            $from = $this->params->date('from', true, null);
            $to = $this->params->date('to', true, null);
            $regions = $this->params->list_int('regions', true, null);
            $data = $this->params->multiple_choice('data', $datas, true, $datas);

            if(sizeof($data) === 0)
                $data = $datas;
        } catch (ParamException $e) {
            $this->json->error(1, "Wrong input");
            return;
        }

        // open database connection
        $this->load->database();

        // read data from database
        $out = $this->regions->get_data($mode, $group, $from, $to, $regions, $data);

        if($out !== null)
            $this->json->success($out);
        else
            $this->json->error(2, "Database error");

    }
}
