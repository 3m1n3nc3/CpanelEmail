<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller 
{ 

    /**
     * This methods allows for employees to login 
     * @return null                 Does not return anything but uses code igniter's view() method to render the page
     */
	public function index()
	{ 
		$viewdata = array();
		
		// If the user is already logged in redirect them 
		if ($this->account_data->logged_in())
		{	
        	$this->account_data->user_redirect();
		}

        // Validate Input
        $this->form_validation->set_error_delimiters('<div class="text-danger form-text text-muted">', '</div>'); 
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

		if($this->input->post())
		{
			if($this->form_validation->run() !== FALSE)
			{
				$login_data['username'] = $this->input->post("username");
				$login_data['email'] 	= $this->input->post("email");
				$login_data['password'] = $this->input->post("password");

				// Check if the user has entered the correct details then set the details to a variable
				if($user = $this->user_model->check_login($login_data)) 
				{	
					// Set the users session
					$this->account_data->login($user); 
					
					// If the user wanted to access a page before login was required redirect them to that page
					if (isset($_SESSION['redirect_to'])) 
					{
						redirect($this->session->userdata('redirect_to'));
					}
					else
					{
						// If the employee does not have the privilege 
						// to view the dashboard redirect them to their profile page
						if (has_privilege('dashboard')) 
						{
							redirect("admin/dashboard");
						}
						else
						{
							redirect("profile/view/my_profile");
						}
					}
				}
				else
				{
					$this->session->set_flashdata('message', alert_notice('Invalid Username or Password', 'error', FALSE, 'FLAT')); 
				}
			}
			else 
			{
				$this->session->set_flashdata('message', alert_notice(validation_errors(), 'error', FALSE, 'FLAT')); 
			}
		}

		$data = array('title' => 'Login - ' . my_config('site_name'), 'page' => 'login');
		// $this->load->view($this->h_theme.'/header', $data);
		$this->load->view($this->h_theme.'/login', array_merge($viewdata, $data));
		// $this->load->view($this->h_theme.'/footer');
	}


    /**
     * This methods will log all users out
     */
	public function logout()
	{
		$this->account_data->user_logout();
		service_point_access_session('clear');
		redirect("/");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
