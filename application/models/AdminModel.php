<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminModel extends CI_Model {

    public function __construct() {
        $this->load->database('default');
        $this->load->library('session');

        // Call the Model constructor
        parent::__construct();
    }

    public function get_all_members() {
        $tags = ['tag =' => 0];
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($tags);
        $objQuery = $this->db->get();
        return $objQuery->result_array();
    }

    public function get_id_member($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $objQuery = $this->db->get();
        return $objQuery->result_array();
    }

    public function insert($arrData) {
        if ($this->db->insert('users', $arrData)) {
            return true;
        } else {
            return false;
        }
    }

    public function update($editData, $id) {
        $this->db->where('id', $id);

        if ($this->db->update('users', $editData)) {
            return true;
        } else {
            return false;
        }
    }

    public function search($key){
        $this->db->select('*');
        $this->db->like('username', $key);
        $this->db->or_like('email',$key);
        $this->db->or_like('firstname',$key);
        $this->db->or_like('lastname',$key);
        $query = $this->db->get('users');
        return $query->result();
    }

    /*
    function delete($id) {
        if ($this->db->delete('users', array('rpin_id' => $id))) {
            return true;
        } else {
            return false;
        }
    }
    */

}

?> 
