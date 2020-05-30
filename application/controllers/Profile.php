<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Admin_Controller { 
 
    public function index()
    {
        redirect('profile/view');
    }

    /**
     * This methods allow viewing of a employee's profile
     * @param  string   $employee_id    Id of the employee records to retrieve 
     * @return null                     Does not return anything but uses code igniter's view() method to render the page
     */
    public function view($uid = '')
    {
        if ($uid == '' OR $uid == 'my_profile') 
        {
            $uid = $this->uid;
        } 
        else
        {
            // Check if the user has permission to access this module and redirect to 401 if not
            error_redirect(has_privilege('manage-employee'), '401'); 
        }

        $user     = $this->account_data->fetch($uid); 
        $viewdata = array('user' => $user);
        $data     = array('title' => $user['firstname'] . ' ' .$user['lastname'] .' - '. my_config('site_name'), 'page' => 'user');
 
        $post = $this->input->post();
        if ($post) 
        {  
            $unique_tel = ($user['phone'] !== $post['phone'] ? '|is_unique[users.phone]' : '');

            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
            $this->form_validation->set_rules('phone', 'Phone number', 'trim|numeric|required|min_length[6]|regex_match[/^[0-9]{11}$/]'.$unique_tel);  

            if ($this->form_validation->run() !== FALSE) 
            {   
                $save = $this->input->post();
                unset($save['update_profile']);

                $msg = 'New user added';
                if ($user['uid']) 
                {
                    $msg = $user['uid'] === $this->uid ? lang('your_profile_updated') : lang('employee_updated');
                    $save['uid'] = $user['uid'];
                }  

                $this->user_model->addEditUser($save);
                $this->session->set_flashdata(array('update_profile'=> TRUE, 'message' => alert_notice($msg, 'success')));
                // redirect("employee");
            } 
        }

        $this->load->view($this->h_theme.'/header', $data);
        $this->load->view($this->h_theme.'/user/view',$viewdata);
        $this->load->view($this->h_theme.'/footer');
    }


    /**
     * Changes the user password
     * @param  string   $employee_id    Id of the employee to delete
     * @return null                     Redirects to the employee list
     */
    function ch_password($uid = '')
    {
        // Check if the user has permission to access this module and redirect to 401 if not
        error_redirect($this->uid === $uid, '401'); 

        $user = $this->user_model->getUser($uid, 1);

        $error            = $message = '';
        $post             = $this->input->post();
        $data['username'] = $user['username'];
        $data['password'] = $post['password'];
        if ($this->user_model->check_login($data)) 
        {  
            $this->form_validation->set_rules('password_new', 'New Password', 'trim|required|min_length[10]');
            $this->form_validation->set_rules('password_rep', 'Repeat Password', 'trim|required|matches[password_new]');

            if ($this->form_validation->run() === FALSE) 
            {    
                $error   .= form_error('password_new');
                $error   .= form_error('password_rep');
                $message .= alert_notice($error, 'error');
            }
            else 
            {
                $args = array(
                    'email'    => $user['email'],  
                    'password' => $post['password_new'],   
                    'domain'   => my_config('primary_server')
                );
                $ch_password   = $this->cpanel->email($args, 'passwd_pop');

                if ($ch_password && empty($ch_password->errors)) 
                { 
                    $save['uid']          = $user['uid'];
                    $save['password']     = $post['password_new'];
                    $save['password_def'] = '';
                    if ($this->user_model->addEditUser($save)) {
                        $message .= alert_notice('Your password has been changed successfully', 'success');
                    }
                } 
                elseif (!empty($ch_password->errors)) 
                { 
                    $message .= alert_notice($ch_password->errors[0], 'error');
                }
            } 
        }
        else
        {
            $error   .= 'Invalid Password!';
            $message .= alert_notice($error, 'error');
        }

        $this->session->set_flashdata(['message' => $message, 'change_password' => true]);
        redirect("profile/view/{$user['uid']}");        
    } 


    /**
     * Logs the user into webmail
     * @param  string   $employee_id    Id of the employee to delete
     * @return null                     Redirects to the employee list
     */
    function webmail($uid = '')
    {
        // Check if the user has permission to access this module and redirect to 401 if not
        error_redirect($this->uid === $uid, '401'); 
        header('Content-type: application/json'); 

        $user = $this->user_model->getUser($uid, 1);

        $s = explode("@",$user['email']);
        array_pop($s); 
        $username = implode("@",$s); 

        $args = array(
            'login'          => $username,  
            'locale'         => 'en',   
            'remote_address' => $this->ip->get(),   
            'domain'         => my_config('primary_server')
        );
        $webmailer = $this->cpanel->session($args, 'create_webmail_session_for_mail_user');

        if ($webmailer && empty($webmailer->errors) && !empty($webmailer->data->token)) 
        { 
            $response['host'] = 'https://' . my_config('primary_server') . ':2096' . $webmailer->data->token . '/login';
            $response['session'] = $webmailer->data->session;
            $response['message'] = alert_notice('Redirecting, Please Wait... <span class="loader"><div class="spinner-border text-white"></div></span>', 'success', FALSE, 'FLAT');
            // $login = $this->curler->ssl_fetch($host, $post);
            // redirect($host);     
        } 
        else
        {
            $response['error'] = alert_notice($webmailer->errors[0] ?? 'Connection Failed', 'error', FALSE, 'FLAT');
        }
        echo json_encode($response);  
    } 
} 
