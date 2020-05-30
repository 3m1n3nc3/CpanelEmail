<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account_Data {

    public $CI;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
    }

    /*
    this checks to see if the admin is logged in
    we can provide a link to redirect to, and for the login page, we have $default_redirect,
    this way we can check if they are already logged in, but we won't get stuck in an infinite loop if it returns false.
     */

    public function logged_in()
    {    
        return (bool) $this->CI->session->userdata('username') or get_cookie('username');
    }     

    public function is_logged_in()
    {

        if ($this->CI->session->has_userdata('username') or get_cookie('username')) 
        {
            $_user = ($this->CI->session->userdata('username') ?? get_cookie('username'));
            $user = $this->fetch($_user);
            if (!$user) 
            {
                redirect('login');
            } 
            else 
            {
                return true;
            }
        } 
        else 
        {
            $this->CI->session->set_userdata('redirect_to', current_url());
            redirect('login');
        }
    } 

    public function user_redirect()
    {
        if ($this->CI->session->has_userdata('username') OR get_cookie('username')) 
        {
            $_user = ($this->CI->session->userdata('username') ?? get_cookie('username'));
            $user = $this->fetch($_user); 
            if ($user) 
            {
                redirect('dashboard');   
            } 
            else 
            {
                redirect('login');
            }
        } 
        else 
        {
            redirect('login');
        }
    } 

    public function fetch($id = null)
    {    
        $data = $this->CI->user_model->getUser($id, 1);  

        if ($data['firstname'] && $data['lastname']) 
        {
            $data['name'] = $data['firstname'] . ' ' . $data['lastname'];
        } 
        elseif ($data['firstname']) 
        {
            $data['name'] = $data['firstname'];
        } 
        elseif ($data['lastname']) 
        {
            $data['name'] = $data['lastname'];
        } 
        elseif ($data) 
        {
            $data['name'] = $data['username'];
        }   
        $data['name'] = $data['name'] ?? NULL;
        return $data;
    }

    public function user_logout()
    {   
        delete_cookie('username');
        $this->CI->session->unset_userdata(['uid', 'username', 'fullname']);
        $this->CI->session->sess_destroy();
    } 

    public function login($user)
    {   
        $data = $this->CI->user_model->fetch_user($user['uid']); 

        $space = $data['firstname'] && $data['lastname'] ? ' ' : '';
        $fullname = ($data['firstname'] || $data['lastname'] ? ($data['firstname'] ?? '') . $space . ($data['lastname'] ?? '') : $data['username']);

        $data = array(
            'uid'      => $data['uid'],
            'username' => $data['username'],
            'fullname' => $fullname 
        ); 
        $this->CI->session->set_userdata($data);
    } 

    public function days_diff($far_date = NULL, $close_date = NULL)
    {   
        $far_date   = $far_date ? $far_date : date('Y-m-d', strtotime('tomorrow'));
        $close_date = $close_date ? $close_date : date('Y-m-d', strtotime('NOW'));

        $far_date   = new DateTime($far_date ? $far_date : date('Y-m-d', strtotime('tomorrow')));
        $close_date = new DateTime($close_date ? $close_date : date('Y-m-d', strtotime('NOW')));        

        if ($far_date > $close_date) {
            return true;
        }
        return false; 
    }

}
