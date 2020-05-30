<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This function will return the configuration settings from the db
     * If key is not provided, then it will fetch all the records form the table.
     * @param string $key
     * @return mixed
     */
    public function get_settings($key = null) 
    {
        $this->db->select('setting_key, setting_value')->from('settings');
        if ($key != null) {
            $this->db->where('setting_key', $key); 
        } 
        else 
        {
            $this->db->order_by('setting_key');
        }

        $query = $this->db->get();

        if ($key != null) 
        {
            return $query->row_array()['setting_value'];
        } 
        else 
        {
            return $query->result_array();
        }
    }

    public function save_settings($data) 
    { 
        foreach (array_keys($data) as $setting_key) 
        {
            if ($this->get_settings($setting_key) != '') 
            { 
                $value = array('setting_value' => $data[$setting_key]);

                $this->db->where('setting_key', $setting_key);
                $this->db->update('settings', $value); 
            } 
            else 
            {
                $value = array('setting_key' => $setting_key, 'setting_value' => $data[$setting_key]);

                $this->db->insert('settings', $value); 
            }
        }  
    }  
}
