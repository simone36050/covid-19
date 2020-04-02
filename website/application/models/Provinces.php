<?php

class Provinces extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected function bind_absolute($sql, $data) {
        if(in_array('cases', $data))
            $sql = str_replace(':data:', ', PT.cases', $sql);

        $sql = str_replace(':join:', '', $sql);
        return $sql;
    }

    protected function bind_relative($sql, $data) {
        if(in_array('cases', $data))
            $sql = str_replace(':data:', ', (PT.cases - IFNULL(PREV.cases, 0)) AS cases', $sql);

        $sql = str_replace(':join:', "LEFT JOIN covid_provincial_trend PREV ON 
                                      PT.province = PREV.province AND 
                                      PT.date = DATE_ADD(PREV.date, INTERVAL 1 DAY)", $sql);

        return $sql;
    }

    protected function bind_order($sql, $group) {
        switch($group) {
            case 'province':
                $sql = str_replace(':order:', 'PT.province, PT.date', $sql);
            break;

            case 'date':
                $sql = str_replace(':order:', 'PT.date, PT.province', $sql);
            break;
        }

        return $sql;
    }

    protected function result_dates_groupped($query, $data) {
        $out = array();

        $actual = null;
        $last = null;

        foreach($query->result() as $res) {
            // is there a new date?
            if($last === null || $res->date !== $last) {
                // only if there is an actual date
                if($actual !== null) {
                    // add date to $out
                    $out[$last] = $actual;
                }

                // reset variables
                $actual = array();
                $last = $res->date;
            }

            // add result to $actual
            $prov = array('province' => intval($res->province));
            if(in_array('cases', $data))
                $prov['cases'] = intval($res->cases);
            $actual[] = $prov;
        }
        // add the remain item
        if($actual !== null)
            $out[$last] = $actual;
        
        return $out;
    }

    protected function result_provinces_groupped($query, $data) {
        $out = array();

        $actual = null;
        $last = null;

        foreach($query->result() as $res) {
            $province = intval($res->province);

            // is there a new province?
            if($last === null || $province !== $last) {
                // only if there is an actual date
                if($actual !== null) {
                    // add date to $out
                    $out[strval($last)] = $actual;
                }

                // reset variables
                $actual = array();
                $last = $province;
            }

            // add result to $actual
            $details = array();
            if(in_array('cases', $data))
                $details['cases'] = intval($res->cases);
            $actual[$res->date] = $details;
        }

        // add the remain item
        if($actual !== null) 
            $out[strval($last)] = $actual;

        return $out;
    }

    public function get_data($mode, $group, $from, $to, $provinces, $data) {
        
        $sql = "SELECT PT.date, PT.province :data: FROM covid_provincial_trend PT
                :join: 
                :conditions:
                ORDER BY :order:";

        // bind data (mode based)
        switch($mode) {
            case 'absolute':
                $sql = $this->bind_absolute($sql, $data);
            break;

            case 'relative':
                $sql = $this->bind_relative($sql, $data);
            break;
        }

        // bind order
        $sql = $this->bind_order($sql, $group);

        // bind provinces list
        $query = null;

        // conditions
        $params = array();
        $values = array();

        // 1. dates
        if($from !== null) {
            $params[] = 'PT.date >= ?';
            $values[] = $from;
        }

        if($to !== null) {
            $params[] = 'PT.date <= ?';
            $values[] = $to;
        }

        // 2. provinces filter
        if($provinces !== null) {
            $params[] = 'PT.province IN ?';
            $values[] = $provinces;
        }

        // bind conditions
        $query = null;
        if(sizeof($params) > 0) {
            $sql = str_replace(':conditions:', 'WHERE ' . join(' AND ', $params), $sql);
            $query = $this->db->query($sql, $values);
        } else {
            $sql = str_replace(':conditions:', '', $sql);
            $query = $this->db->query($sql);
        }

        // error handler
        if($this->db->error()['code'] !== 0) 
            return null;

        // result reader
        $out = null;
        switch($group) {
            case 'province':
                $out = $this->result_provinces_groupped($query, $data);
            break;

            case 'date':
                $out = $this->result_dates_groupped($query, $data);
            break;
        }

        return $out;
    }

}