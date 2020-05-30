<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Admin_Controller 
{ 

    /**
     * This will list all employees 
     * @return null             Does not return anything but uses code igniter's view() method to render the page
     */
    public function index()
    {
        // Check if the user has permission to access this module and redirect to 401 if not
        error_redirect(has_privilege('manage-employee'), '401'); 

        $config['base_url']   = site_url('users/list/');
        $config['total_rows'] = count($this->user_model->get_users()); 

        $this->pagination->initialize($config);
        $_page = $this->uri->segment(3, 0);

        $users = $this->user_model->get_users(['page' => $_page]); 
        $viewdata  = array('users' => $users); 
        $viewdata['pagination'] = $this->pagination->create_links(); 

        $data = array('title' => 'Users - '. my_config('site_name'), 'page' => 'users');
        $this->load->view($this->h_theme.'/header', $data);
        $this->load->view($this->h_theme.'/user/list',$viewdata);
        $this->load->view($this->h_theme.'/footer');
    }


    /**
     * An alias of the index 
     * @return null             Does not return anything but uses code igniter's view() method to render the page
     */
    public function list()
    {
        // Check if the user has permission to access this module and redirect to 401 if not
        error_redirect(has_privilege('manage-employee'), '401'); 

        $config['base_url']   = site_url('users/list/');
        $config['total_rows'] = count($this->user_model->get_users()); 

        $this->pagination->initialize($config);
        $_page = $this->uri->segment(3, 0);

        $users = $this->user_model->get_users(['page' => $_page]); 
        $viewdata  = array('users' => $users); 
        $viewdata['pagination'] = $this->pagination->create_links(); 

        $data = array('title' => 'Users - '. my_config('site_name'), 'page' => 'users');
        $this->load->view($this->h_theme.'/header', $data);
        $this->load->view($this->h_theme.'/user/list',$viewdata);
        $this->load->view($this->h_theme.'/footer');
    }


    /**
     * This methods allow for adding and updating employees
     * @param  string   $employee_id    Id of the employee records to retrieve if updating
     * @return null                     Does not return anything but uses code igniter's view() method to render the page
     */
    public function add($uid = '')
    {
        if ($uid == 'my_profile') 
        {
            $uid = $this->uid;
        } 
        else
        {
            // Check if the user has permission to access this module and redirect to 401 if not
            error_redirect(has_privilege('manage-employee'), '401'); 
        } 
        $user = $this->user_model->getUser($uid, 1);
        $viewdata = array('user' => $user);
        $data = array('title' => 'Add User - '. my_config('site_name'), 'page' => 'users');

        if ($this->input->post()) {
            $up  = $this->input->post('phone') != $user['phone'] ? '|is_unique[users.phone]' : ''; 
            $uus = $this->input->post('username') != $user['username'] ? '|is_unique[users.username]' : ''; 

            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            $this->form_validation->set_rules('username', lang('username'), 'trim|alpha_dash|required'.$uus);  
            $this->form_validation->set_rules('phone', lang('phone'), 'trim|numeric|required|min_length[6]|regex_match[/^[0-9]{11}$/]'.$up);  

            if ($this->form_validation->run() !== FALSE) 
            {   
                $save = array(
                    'username' => strtolower($this->input->post("username")),  
                    'firstname' => $this->input->post("firstname"), 
                    'lastname' => $this->input->post("lastname"), 
                    'phone' => $this->input->post("phone"),  
                    'address' => $this->input->post("address") 
                ); 

                $msg = 'New user added';
                if ($user['uid']) 
                {
                    $msg = $user['username'] . lang('has_been_updated');
                    $save['uid'] = $user['uid'];
                } 

                $this->user_model->addEditUser($save);
                $this->session->set_flashdata('message', alert_notice($msg, 'success'));
                redirect("users");
            }
        }

        $this->load->view($this->h_theme.'/header', $data);
        $this->load->view($this->h_theme.'/user/add',$viewdata);
        $this->load->view($this->h_theme.'/footer');
    } 

    /**
     * This methods allow for adding and updating employees
     * @param  string   $employee_id    Id of the employee records to retrieve if updating
     * @return null                     Does not return anything but uses code igniter's view() method to render the page
     */
    public function generate($uid = '')
    {
        if ($uid == 'my_profile') 
        {
            $uid = $this->uid;
        } 
        else
        {
            // Check if the user has permission to access this module and redirect to 401 if not
            error_redirect(has_privilege('manage-employee'), '401'); 
        } 
        $user     = $this->user_model->getUser($uid, 1);
        $viewdata = array('user' => $user);
        $data     = array('title' => 'Generate Accounts - '. my_config('site_name'), 'page' => 'users');
        $saved    = FALSE;
        $errors   = NULL; 

        if ($post = $this->input->post()) { 

            foreach ($post['email'] as $key => $value) 
            { 
                $real_email = $post['email'][$key] . $post['emailx'][$key];
                $s = explode("@",$real_email);
                array_pop($s); 
                $username = implode("@",$s); 

                $save = array( 
                    'email'     => $real_email,
                    'phone'     => $post['phone'][$key], 
                    'username'  => $username,  
                    'firstname' => $post['firstname'][$key], 
                    'lastname'  => $post['lastname'][$key]
                );
                
                if ($post['password'][$key]) 
                {
                    $save['password_def'] = $post['password'][$key];
                    $save['password']     = MD5($post['password'][$key]);
                }

                $args = array(
                    'email'     => $post['email'][$key], 
                    'password'  => $post['password'][$key] ? $post['password'][$key] : generate_token(FALSE, 1, 8), 
                    'domain'    => my_config('primary_server')
                );

                $propagate_email = $this->cpanel->email($args);
                if ($propagate_email && empty($propagate_email->errors)) {
                    $saved = $this->user_model->addEditUser($save);
                } 
                elseif (!empty($propagate_email->errors)) 
                {
                    $errors = $propagate_email->errors;
                }
                else
                {
                    $errors[0] = 'Server Error!';
                }
            }  

            if ($saved) {
                $msg = count($post['email']).' new users added'; 

                $this->session->set_flashdata('message', alert_notice($msg, 'success'));
                redirect("users");
            } 
            elseif ($errors) 
            {
                $this->session->set_flashdata('message', alert_notice($errors[0], 'danger'));
            }
        }

        $this->load->view($this->h_theme.'/header', $data);
        $this->load->view($this->h_theme.'/user/generate',$viewdata);
        $this->load->view($this->h_theme.'/footer');
    } 


    /**
     * Deletes an employee record
     * @param  string   $employee_id    Id of the employee to delete
     * @return null                     Redirects to the employee list
     */
    function delete($uid = '')
    {
        // Check if the user has permission to access this module and redirect to 401 if not
        error_redirect(has_privilege('manage-employee'), '401'); 

        $user = $this->user_model->getUser($uid, 1);
        $args = array(
            'email'  => $user['email'],  
            'flags'  => 'dont_passwd',   
            'domain' => my_config('primary_server')
        );
        $rm_email = $this->cpanel->email($args, 'delete_pop');
        if ($rm_email && empty($rm_email->errors)) 
        {
            if ($this->user_model->deleteUser($uid)) 
            {
                $message = alert_notice(lang('employee_deleted'));
            }
        } 
        elseif (!empty($rm_email->errors)) 
        { 
            $message = alert_notice($rm_email->errors[0], 'error');
        }
        
        $this->session->set_flashdata('message', $message, 'success');
        redirect("users"); 
    } 
} 
