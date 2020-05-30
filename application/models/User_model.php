<?php

class User_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     * This function takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    
    function get_users($data = array())
    {        
        if (!isset($data['bypass_role']) && isset($this->this_role))
        {
            $this->db->where('users.role <=', $this->this_role);
        }

        if (isset($data['page'])) {
            $this->db->limit($this->config->item('per_page'), $data['page']);
        }

        $this->db->order_by('uid DESC');
        $query = $this->db->from('users')->get();
        $data = array();

        foreach (@$query->result() as $row)
        {
            $data[] = $row; 
        }
        if(count($data))
            return $data;
        return false;
    } 

    /**
     * This function takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    
    function fetch_user($uid = '')
    {         
        $query = $this->db->get_where('users', array('uid' => $uid));
        return $query->row_array();
    } 

    function getUser($user, $get_array = null)
    {   
        if (!isset($user['bypass_role']) && isset($this->this_role))
        {
            $this->db->where('users.role <=', $this->this_role);
        }

        if (isset($user['id'])) 
        {
            $user = $user['id'];
        }

        $query = $this->db->get_where('users', array('uid' => $user));
        if ($get_array) 
        {
            return $query->row_array();
        }
        return $query->result();
    }

    function addEditUser($data)
    {         
        if (!isset($data['bypass_role']) && isset($this->this_role))
        {
            $this->db->where('users.role <=', $this->this_role);
        }

        if (isset($data['uid'])) 
        { 
            $this->db->where('uid', $data['uid']);
            $this->db->update('users', $data); 
            return $this->db->affected_rows();
        }
        else
        {
            $this->db->insert('users', $data);
            return $this->db->insert_id();
        }
    } 

    function deleteUser($user)
    {   
        if (!isset($user['bypass_role']) && isset($this->this_role))
        {
            $this->db->where('users.role <=', $this->this_role);
        }

        if (isset($user['id'])) 
        {
            $user = $user['id'];
        }

        $this->db->delete('users', array('uid' => $user));
        return $this->db->affected_rows();
    } 

    function getUserRole($uid)
    {   
        return $this->db->where('uid', $uid)->select('role, role_id')->from('users')->get()->row_array(); 
    }

    public function check_login($data) 
    {
        $this->db->select('uid, username, password, email');
        $this->db->from('users');
        $this->db->where('email', $data['username']);
        $this->db->or_where('username', $data['username']);
        $this->db->where('password', MD5($data['password']));
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) 
        {
            return $query->row_array();
        } 
        else 
        {
            return false;
        }
    } 
} 
