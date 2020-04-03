<?php

class Regions extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    protected function bind_absolute($sql, $data) {
        $params = '';

        if(in_array('icu', $data)) 
            $params .= ', RT.intensive_care_unit';

        if(in_array('hospitalized', $data))
            $params .= ', RT.hospitalized_no_icu';

        if(in_array('isolation', $data))
            $params .= ', RT.isolation';

        if(in_array('positives', $data))
            $params .= ', RT.positives';

        if(in_array('cures', $data))
            $params .= ', RT.cures';

        if(in_array('dead', $data))
            $params .= ', RT.dead';

        if(in_array('swabs', $data))
            $params .= ', RT.swabs';

        $sql = str_replace(':data:', $params, $sql);
        $sql = str_replace(':join:', '', $sql);

        return $sql;
    }

    protected function bind_relative($sql, $data) {
        $params = '';

        if(in_array('icu', $data)) 
            $params .= ', (RT.intensive_care_unit - IFNULL(PREV.intensive_care_unit, 0)) AS intensive_care_unit';

        if(in_array('hospitalized', $data))
            $params .= ', (RT.hospitalized_no_icu - IFNULL(PREV.hospitalized_no_icu, 0)) AS hospitalized_no_icu';

        if(in_array('isolation', $data))
            $params .= ', (RT.isolation - IFNULL(PREV.isolation, 0)) AS isolation';

        if(in_array('positives', $data))
            $params .= ', (RT.positives - IFNULL(PREV.positives, 0)) AS positives';

        if(in_array('cures', $data))
            $params .= ', (RT.cures - IFNULL(PREV.cures, 0)) AS cures';

        if(in_array('dead', $data))
            $params .= ', (RT.dead - IFNULL(PREV.dead, 0)) AS dead';

        if(in_array('swabs', $data))
            $params .= ', (RT.swabs - IFNULL(PREV.swabs, 0)) AS swabs';

        $sql = str_replace(':data:', $params, $sql);
        $sql = str_replace(':join:', "LEFT JOIN covid_regional_trend PREV ON
                                      RT.region = PREV.region AND
                                      RT.date = DATE_ADD(PREV.date, INTERVAL 1 DAY)", $sql);

        return $sql;
    }

    protected function bind_order($sql, $group) {
        switch($group) {
            case 'region':
                $sql = str_replace(':order:', 'RT.region, RT.date', $sql);
            break;

            case 'date':
                $sql = str_replace(':order:', 'RT.date, RT.region', $sql);
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
            $prov = array('region' => intval($res->region));

            // cast all data
            if(in_array('icu', $data)) 
                $prov['icu'] = intval($res->intensive_care_unit);

            if(in_array('hospitalized', $data))
                $prov['hospitalized'] = intval($res->hospitalized_no_icu);

            if(in_array('isolation', $data))
                $prov['isolation'] = intval($res->isolation);

            if(in_array('positives', $data))
                $prov['isolation'] = intval($res->isolation);

            if(in_array('cures', $data))
                $prov['cures'] = intval($res->cures);

            if(in_array('dead', $data))
                $prov['dead'] = intval($res->dead);

            if(in_array('swabs', $data))
                $prov['swabs'] = intval($res->swabs);

            $actual[] = $prov;
        }
        // add the remain item
        if($actual !== null)
            $out[$last] = $actual;

        return $out;        
    }

    protected function result_regions_groupped($query, $data) {
        $out = array();

        $actual = null;
        $last = null;

        foreach($query->result() as $res) {
            $regions = intval($res->region);

            // is there a new regions?
            if($last === null || $regions !== $last) {
                // only if there is an actual date
                if($actual !== null) {
                    // add date to $out
                    $out[strval($last)] = $actual;
                }

                // reset variables
                $actual = array();
                $last = $regions;
            }

            // add result to $actual
            $details = array();

            // cast all data
            if(in_array('icu', $data)) 
                $details['icu'] = intval($res->intensive_care_unit);

            if(in_array('hospitalized', $data))
                $details['hospitalized'] = intval($res->hospitalized_no_icu);

            if(in_array('isolation', $data))
                $details['isolation'] = intval($res->isolation);

            if(in_array('positives', $data))
                $details['isolation'] = intval($res->isolation);

            if(in_array('cures', $data))
                $details['cures'] = intval($res->cures);

            if(in_array('dead', $data))
                $details['dead'] = intval($res->dead);

            if(in_array('swabs', $data))
                $details['swabs'] = intval($res->swabs);

            $actual[$res->date] = $details;
        }

        // add the remain item
        if($actual !== null) 
            $out[strval($last)] = $actual;

        return $out;
    }

    public function get_data($mode, $group, $from, $to, $regions, $data) {

        $sql = "SELECT RT.date, RT.region :data: FROM covid_regional_trend RT
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

        $query = null;

        // conditions
        $params = array();
        $values = array();

        // 1. dates
        if($from !== null) {
            $params[] = 'RT.date >= ?';
            $values[] = $from;
        }

        if($to !== null) {
            $params[] = 'RT.date <= ?';
            $values[] = $to;
        }

        // 2. regions filter
        if($regions !== null) {
            $params[] = 'RT.region IN ?';
            $values[] = $regions;
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
            case 'region':
                $out = $this->result_regions_groupped($query, $data);
            break;

            case 'date':
                $out = $this->result_dates_groupped($query, $data);
            break;
        }

        return $out;
    }

}