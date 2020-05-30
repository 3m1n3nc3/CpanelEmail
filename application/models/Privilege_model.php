<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Privilege_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This function takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null) {

        $this->db->select('*')->from('privileges'); 
        if ($id !== null) 
        {
            $this->db->where('id', $id); 
        }  

        $query = $this->db->get();
        if ($id !== null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
        return $query->row_array();
    } 

    public function verify($name) {

        $this->db->select('id')->from('privileges'); 
        $this->db->where('title', $name); 

        $query = $this->db->get();
        return $query->row_array();
    } 

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $id
     */
    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('privileges', $data);
            return $this->db->affected_rows();
        } else {
            $this->db->insert('privileges', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
    } 

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('privileges');
        return $this->db->affected_rows();
    }
}
