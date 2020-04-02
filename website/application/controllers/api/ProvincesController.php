<?php

class ProvincesController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library('json');
        $this->load->library('params');
    }

    public function index() {
        $this->load->model('provinces');

        $modes = array('absolute', 'relative', 'range');
        $groups = array('province', 'date');
        $datas = array('cases');

        // read input
        try {
            $mode = $this->params->choice('mode', $modes, true, 'absolute');
            $group = $this->params->choice('group', $groups, true, 'province');
            $from = $this->params->date('from', true, null);
            $to = $this->params->date('to', true, null);
            $provinces = $this->params->list_int('provinces', true, null);
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
        $out = $this->provinces->get_data($mode, $group, $from, $to, $provinces, $data);

        if($out !== null)
            $this->json->success($out);
        else
            $this->json->error(2, "Database error");
    }
}
