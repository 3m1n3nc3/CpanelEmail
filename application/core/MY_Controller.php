<?php 

class MY_Controller extends CI_Controller
{ 
    public function __construct()
    {
        parent::__construct();   
        $this->config->load('config_');
        $this->load->library('cpanel'); 
 
        if ($this->input->get('set_theme')) 
        {
            $this->session->set_userdata('site_theme', $this->input->get('set_theme'));
        }

        if ($this->session->userdata('site_theme')) 
        {
            $this->h_theme = $this->session->userdata('site_theme');
        }
        else
        {
            $this->h_theme = 'modern';
        }

        $this->uid         = $this->session->userdata('uid');
        $this->username    = $this->session->userdata('username');
        $this->fullname    = $this->session->userdata('fullname');
        $this->logged_user = $this->user_model->getUser($this->uid, 1);  
    } 
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct(); 
        $this->account_data->is_logged_in(); 
    } 
}  
