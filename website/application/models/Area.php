<?php

class Area extends CI_Model {

    public function list_only_region($regions) {
        // statement
        $sql = "SELECT R.id, R.name FROM covid_regions R";
        $query = null;

        if($regions !== null) {
            // with filters
            $sql .= " WHERE R.id IN ?";
            $query = $this->db->query($sql, array($regions));
        } else {
            // without filter
            $query = $this->db->query($sql);
        }
        
        // error handler
        if($this->db->error()['code'] !== 0) 
            return null;

        // output generator
        $out = array();
        foreach($query->result() as $row) 
            $out[] = array(
                'id' => intval($row->id),
                'name' => $row->name);
                
        return $out;
    }

    public function list_regions_provinces($regions) {
        $regs = $this->list_only_region($regions);
        if($regs === null)
            return null;

        $sql = "SELECT P.id, P.name, P.region FROM covid_provinces P";
        $query = null;

        if($regions !== null) {
            // with filters
            $sql .= " WHERE P.region IN ?";
            $query = $this->db->query($sql, array($regions));
        } else {
            // without filters
            $query = $this->db->query($sql);
        }

        // error handler
        if($this->db->error()['code'] !== 0) 
            return null;

        // parser
        $out = array();
        foreach($regs as $reg) {
            $reg['provinces'] = array();
            $out[$reg['id']] = $reg;
        }

        foreach($query->result() as $row)
            $out [intval($row->region)] ['provinces'] [] = array(
                'id' => intval($row->id),
                'name' => $row->name
            );

        return array_values($out);
    }

}

