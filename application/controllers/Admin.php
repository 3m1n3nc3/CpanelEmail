<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller { 

    /**
     * Displays the dashboard, the default landing page for administrators 
     * @return null           Does not return anything but uses code igniter's view() method to render the page
     */
    public function dashboard()
    { 
        // Check if the user has permission to access this module and redirect to 401 if not
        error_redirect(has_privilege('dashboard'), '401');
            
        $data = array(
            'title'        => my_config('site_name'), 
            'page'         => 'dashboard' 
        );
        $this->load->view($this->h_theme.'/header', $data);
 
        $viewdata = array(  
        );
        $this->load->view($this->h_theme.'/welcome_message', $viewdata);
        $this->load->view($this->h_theme.'/footer', $viewdata);
        $this->session->set_userdata('show_guide',true);
    }


    /**
     * This methods handles the global configuration of the the whole system
     * @param  string   $step   The configuration forms are broken down into 
     *                          steps for maintainability, this will set the current step   
     * @return null             Does not return anything but uses code igniter's view() method to render the page
     */
    public function configuration($step = 'main')
    { 
        // Check if the user has permission to access this module and redirect to 401 if not        
        error_redirect(has_privilege('manage-configuration'), '401');  

        $data = array(
            'title'        => 'Site Configuration - ' . my_config('site_name'),  
            'page'         => 'configuration',
            'step'         => $this->input->post('step') ? $this->input->post('step') : $step,
            'enable_steps' => 1
        ); 

        $this->form_validation->set_error_delimiters('<div class="text-danger form-text text-muted">', '</div>'); 
 
        if (!$data['enable_steps'] || $data['step'] == 'main') 
        { 
            $this->form_validation->set_rules('value[site_name]', 'Site Name', 'trim|required|alpha_numeric_spaces'); 
        }

        if (!$data['enable_steps'] || $data['step'] == 'payment')
        { 
            $this->form_validation->set_rules('value[site_currency]', 'Site Currency', 'trim|alpha|required|max_length[3]'); 
            $this->form_validation->set_rules('value[currency_symbol]', 'Currency Symbol', 'trim');  
            $this->form_validation->set_rules('value[payment_ref_pref]', 'Reference Prefix', 'trim|alpha_dash'); 
            $this->form_validation->set_rules('value[paystack_public]', 'Paystack Public Key', 'trim|alpha_dash'); 
            $this->form_validation->set_rules('value[paystack_secret]', 'Paystack Secret Key', 'trim|alpha_dash'); 
            $this->form_validation->set_rules('value[checkout_info]', 'Checkout Info', 'trim'); 
        }

        if (!$data['enable_steps'] || $data['step'] == 'contact') 
        { 
            $this->form_validation->set_rules('value[contact_email]', lang('contact').' '.lang('email_address'), 'trim|valid_emails'); 
            $this->form_validation->set_rules('value[contact_phone]', lang('contact').' '.lang('phone'), 'trim'); 
            $this->form_validation->set_rules('value[contact_days]', lang('contact').' ' .lang('days'), 'trim'); 
            $this->form_validation->set_rules('value[contact_facebook]', lang('site').' ' .lang('facebook'), 'trim'); 
            $this->form_validation->set_rules('value[contact_twitter]', lang('site').' ' .lang('twitter'), 'trim'); 
            $this->form_validation->set_rules('value[contact_instagram]', lang('site').' ' .lang('instagram'), 'trim'); 
            $this->form_validation->set_rules('value[contact_address]', lang('contact').' '.lang('address'), 'trim'); 
        }   
        if (!$data['enable_steps'] || $data['step'] == 'cpanel') 
        { 
            $this->form_validation->set_rules('value[primary_server]', 'Server Name', 'valid_url'); 
        } 

        if ($this->form_validation->run() === FALSE) 
        { 
            if ($this->input->post('value')) 
            {
                $this->session->set_flashdata('message', alert_notice(lang('input_has_errors'), 'danger'));
            }
        } 
        else 
        { 
            unset($_POST['step']);
            $resize = ['width'   => 150, 'height' => 150];
            $x_resize = ['width' => 30, 'height'  => 30];
            $b_resize = ['width' => 800, 'height' => 800]; 
            $this->creative_lib->upload('features_banner', my_config('features_banner'), 'features_banner', NULL, $b_resize, ['value' => 'features_banner']);
            $this->creative_lib->upload('breadcrumb_banner', my_config('breadcrumb_banner'), 'breadcrumb_banner', NULL, $b_resize, ['value' => 'breadcrumb_banner']);
            $this->creative_lib->upload('site_logo', my_config('site_logo'), 'site_logo', NULL, $resize, ['value' => 'site_logo']);
            $this->creative_lib->upload('favicon', my_config('favicon'), 'favicon', NULL, $x_resize, ['value' => 'favicon']);

            if ($this->creative_lib->upload_errors() === FALSE)
            {
                $save = $this->input->post('value'); 
                $this->setting_model->save_settings($save);
                $this->session->set_flashdata('message', $this->my_config->alert(lang('configuration_saved'), 'success'));
                redirect('admin/configuration/'.$step); 
            }

            $process_complete = TRUE;
        }   

        $this->load->view($this->h_theme.'/header', $data);       
        $this->load->view($this->h_theme.'/dashboard/admin_configuration', $data);       
        $this->load->view($this->h_theme.'/footer', $data);  
    }  


    /**
     * This methods allows for assigning permissions and privileges to employees
     * @param  string   $action     Specifies if you are to {create} and update or {assign} privileges
     * @param  string   $action_id  Id of the customer to assign privilege or privilege to update
     * @return null                 Does not return anything but uses code igniter's view() method to render the page
     */
    public function permissions($action = 'create', $action_id = '')
    {
        // Check if the user has permission to access this module and redirect to 401 if not
        error_redirect(has_privilege('manage-privilege'), '401');  

        $data = $this->account_data->fetch($action_id);

        $data['privileges']  = $this->privilege_model->get($action == 'create' ? $action_id : '');
        $data['action_id']   = $action_id;
        $data['action'] = $action;

        if ($action == 'assign') 
        {
            $u = $this->account_data->fetch($action_id);
            error_redirect(has_privilege('super') || !has_privilege('super', o2Array($u)), '401'); 
        }

        // Generate or assign privileges  
        if ($this->input->post('action')) 
        {
            $this->form_validation->set_error_delimiters('<div class="text-danger form-text text-muted">', '</div>'); 
            if ($action == 'assign') 
            {
                $this->form_validation->set_rules('id', lang('employee_id'), 'trim|numeric|required'); 
            }
            elseif ($action == 'create') 
            {
                $this->form_validation->set_rules('title', lang('title'), 'trim|required'); 
                $this->form_validation->set_rules('permissions', lang('permission'), 'trim|required'); 
                $this->form_validation->set_rules('info', lang('description'), 'trim'); 
            } 

            if ($this->form_validation->run() !== FALSE) 
            { 
                $save = $this->input->post();
                if ($action == 'assign') 
                { 
                    $p = $this->privilege_model->get($save['role_id']);
                    $u = $this->account_data->fetch($save['id']); 
                    $msg = sprintf(lang('user_granted_privilege'), $u['name'], $p['title']);

                    $save['uid'] = $u['uid'];
 
                    unset($save['action'], $save['id']);
                    $this->user_model->addEditUser($save);
                    $this->session->set_flashdata('message', alert_notice($msg, 'info'));
                } 
                elseif ($action == 'create') 
                {
                    $msg = lang('new_privilege_created');

                    if ($data['privileges']['id']) 
                    {
                        $save['id'] = $data['privileges']['id'];
                        $msg = lang('privilege_updated');
                    }
                    $save['permissions'] = encode_privilege(str_ireplace(', ', ',', $save['permissions']));
                    
                    unset($save['action']);
                    $this->privilege_model->add($save);
                    $this->session->set_flashdata('message', alert_notice($msg, 'info'));
                } 
                redirect(uri_string());
            }
        }
        elseif ($action == 'delete') 
        {
            $this->privilege_model->remove($action_id);
            $this->session->set_flashdata('message', alert_notice(lang('privilege').' '.lang('deleted'), 'info'));
            redirect(site_url('admin/permissions/create'));
        }
 
        $head_data = array(
            'title' => 'Privileges - '. my_config('site_name'), 
            'page' => 'privilege', 
            'sub_page_title' => lang('privileges')
        );

        $this->load->view($this->h_theme.'/header', $head_data);
        $this->load->view($this->h_theme.'/dashboard/permissions',$data);
        $this->load->view($this->h_theme.'/footer');
    }
} 
