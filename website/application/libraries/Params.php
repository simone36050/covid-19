<?php

class ParamException extends Exception {
    
    public function __construct() {
        parent::__construct();
    }

}


class Params {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    private function filter($data, $optional=false, $default=null) {
        if($data === null) {
            if($optional)
                return true;
            else
                throw new ParamException();
        }

        return false;
    }

    public function flag($name) {
        return $this->CI->input->post_get($name) !== null;
    }

    public function list_int($name, $optional=false, $default=null) {
        $data = $this->CI->input->post_get($name);
        if($this->filter($data, $optional, $default))
            return $default;
        
        if(!is_array($data))
            throw new ParamException();

        for($i = 0; $i < sizeof($data); $i++) {
            if(filter_var($data[$i], FILTER_VALIDATE_INT) === false)
                throw new ParamException();
            $data[$i] = intval($data[$i]);
        }

        return $data;
    }

    public function choice($name, $options, $optional=false, $default=null) {
        $data = $this->CI->input->post_get($name);
        if($this->filter($data, $optional, $default))
            return $default;

        if(filter_var($data, FILTER_SANITIZE_STRING) === false)
            throw new ParamException();

        if(!in_array($data, $options))
            throw new ParamException();

        return $data;
    }

    public function date($name, $optional=false, $default=null) {
        $date = $this->CI->input->post_get($name);
        if($this->filter($date, $optional, $default))
            return $default;

        if(filter_var($date, FILTER_SANITIZE_STRING) === false)
            throw new ParamException();

        if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
            throw new ParamException();

        return $date;
    }

    public function multiple_choice($name, $options, $optional=false, $default=null) {
        $data = $this->CI->input->post_get($name);
        if($this->filter($data, $optional, $default))
            return $default;

        if(!is_array($data))
            throw new ParamException();

        for($i = 0; $i < sizeof($data); $i++) {
            if(filter_var($data[$i], FILTER_SANITIZE_STRING) === false)
                throw new ParamException();
            if(!in_array($data[$i], $options))
                throw new ParamException();
        }

        return $data;
    }

}
