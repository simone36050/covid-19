<?php

class Json {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    protected function header() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
    }

    public function success($data) {
        $this->header();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    public function error($id, $msg) {
        $this->header();
        $this->success(array(
            'error' => true,
            'code' => $id,
            'message' => $msg
        ));
    }

}
