<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Connect extends MY_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}  


    /**
     * Handles ajax image uploads
     * @param  string 	$endpoint_id	Specifies the id of the content receiving the image record
     * @param  string 	$endpoint		Specifies the type of content receiving the image record
     * @return null     Does not return anything but echoes a JSON Object with a response
     */
	public function upload_image($endpoint_id = '', $endpoint = 0)
	{ 	 
        header('Content-type: application/json');  
        
		if ($endpoint === 'customer' OR $endpoint === 'passport') 
		{ 
			$user_id  = $endpoint_id ? $endpoint_id : $this->session->userdata('cusername');
			$data     = $this->account_data->fetch($user_id, 1); 
	        $sub_folder = $data['customer_username'] . $data['customer_id'] . '/';  
		}
		elseif ($endpoint === 'employee') 
		{ 
			$user_id  = $endpoint_id ? $endpoint_id : $this->session->userdata('username');
			$data     = $this->account_data->fetch($user_id); 
	        $sub_folder  = $data['employee_username'] . $data['employee_id'] . '/'; 	 
		}
		else 
		{  
			// page, room
	        $page = $this->content_model->get(array('id' => $endpoint_id));
	        // $room = $this->room_model->getRoom(array('id' => $endpoint_id));
	        // $roomType = $this->room_model->getRoomType($endpoint_id/*$room['room_type']*/, 1);
            if ($endpoint === 'page') 
            {
                $data = $this->content_model->get(array('id' => $endpoint_id));
            }
            elseif ($endpoint === 'facility') 
            {
                $data = $this->content_model->get_facilities(array('id' => $endpoint_id));
            }
            else 
	        {
	        	$data = $this->room_model->getRoomType($endpoint_id, 1);
	        }
	        $sub_folder  = $endpoint . '/';
		} 

		$table_index = ($endpoint === 'page' ? 'banner' : ($endpoint === 'passport' ? 'passport' : 'image')); 

		$upload_type = $this->input->post('set_type');
		if ($upload_type === 'cover') 
        {
            $folder = 'covers/';
        } 
        elseif ($upload_type === 'avatar') 
        {
            $folder = 'avatars/';
        } 
        else 
		{
			$folder = $endpoint . '/'; 
		}

		// Set the upload directory
	    $_config['upload_path'] = './uploads/' . $folder . $sub_folder; 

		if (isset($data)) { 

			// Check if this upload is ajax
			$file = $this->input->post('ajax_image');
			if ($file) 
			{
			  	$ajax_image_ = explode(';', $file);
			  	$ajax_image_ = isset($ajax_image_[1]) ? $ajax_image_[1] : null; 
			}

			if (isset($ajax_image_))
			{ 
				list($type, $file) = explode(';', $file);
				list(, $file) = explode(',', $file);
				$image = base64_decode($file);
				$new_image = mt_rand().'_'.mt_rand().'_'.mt_rand().'_p.png';

			  	// Save the new image to the upload directory              
			  	if ($image)
			  	{
	                if ( ! $this->creative_lib->create_dir($_config['upload_path']))
	                {
	                    $data['error'] = $this->my_config->alert('The upload destination folder does not appear to be writable.', 'danger'); 
	                }
	                else
	                {
	                    $this->creative_lib->delete_file('./' . $data[$table_index]);

		                if ( ! file_put_contents($_config['upload_path'] . $new_image, $image) )
		                {
		                    $data['error'] = $this->my_config->alert('The file could not be written to disk.', 'danger'); 
		                }
		                else
		                {
		                    $data_img = 'uploads/' . $folder . $sub_folder . $new_image;

							if ($endpoint === 'customer' OR $endpoint === 'passport') 
							{
								$this->customer_model->add_customer(['cid' => $data['customer_id'], $table_index => $data_img]);
							}
							elseif ($endpoint === 'employee') 
							{ 	 
								$this->employee_model->addEditEmployee(['employee_id' => $data['employee_id'], $table_index => $data_img]);
							} else {
                                // Create a new $data instance
                                $data = ['id' => $data['id'], $table_index => $data_img];
                            }
                            
                            if ($endpoint === 'page') 
					        { 
								$this->content_model->add($data);
					        }
                            elseif ($endpoint === 'room')
                            { 
                                $this->room_model->editRoomType($data);
                            }  
                            elseif ($endpoint === 'facility')
                            { 
                                $this->content_model->add_facility($data);
                            }  

		                    chmod($_config['upload_path'].'/'.$new_image, 0777); 
		                    $data['success'] = $this->my_config->alert('Your upload was completed successfully.', 'success');
		                }
	                }
	            } 
			} 
			elseif (empty($ajax_image_)) 
			{
	            $data['error'] = $this->my_config->alert('We were unable to process this upload, maybe you did not select a file.', 'danger'); 
			}
		} 
		else 
		{
            $data['error'] = $this->my_config->alert('Unable to find parent content, please contact your server admin', 'danger'); 
		}

		echo json_encode($data, JSON_FORCE_OBJECT);
		return;
	}


    /**
     * Receives a request from ajax and saves the current order of the items 
     * @return null     Does not return anything but echoes a JSON Object with a response
     */
	public function sortable()
	{	
		$items = $this->input->post(NULL, TRUE);
		$i = 1;
		foreach ($items['item'] AS $item) 
		{
			$this->content_model->add(['id' => $item, 'priority' => $i]);
			$i++;
		}
		echo json_encode(array('response' => TRUE));
	} 


    /**
     * Deletes items from the database, request is sent from ajax
     * @param  string 	$endpoint_id	Specifies the id of the content receiving the image record
     * @param  string 	$endpoint		Specifies the type of content receiving the image record
     * @return null     Does not return anything but echoes a JSON Object with the current status of the upload
     */
	public function deleteItem()
	{
        header('Content-type: application/json');  

        $response['status']   = 0;  
        $response['msg']      =
        $response['response'] = FALSE;

        if ($this->uid) 
        {
    		$type = $this->input->post('type');
    		$data['id']     = $this->input->post('id'); 
    		$data['action'] = $this->input->post('action');
    		$data['init'] 	= $this->input->post('init');
     		
     		if ($type == 'page') 
     		{
     			$query = array('id' => $data['id']);
            	$page = $this->content_model->get($query);

            	// Delete the images associated with this item
     			$this->creative_lib->delete_file($page['banner']);

     			// Delete all records associated with this item
     			if (!$page['parent']) {
     				$this->content_model->remove(['parent' => $page['safelink']]); 
     			}
     			$this->content_model->remove($data['id']);
     		}
            $response = array('response' => true, 'msg' => '', 'status' => 1);
        }
        echo json_encode($response);
        return;
	}


    /**
     * Receives ajax request for country, state, and cities worldwide
     * @param  string   $local      specifies the local to return [countries|states|cities]
     * @param  string   $parent_id  If set will return the local for the set parent
     * @return NULL     Echoes a json string containing the data presented by the relevant helper
     */
    public function fetch_locale($locale = '', $parent_id = '')
    {
        if ($locale == 'countries') 
        {
            $data['response'] = select_countries();
        }
        elseif ($locale == 'states') 
        {
            $data['response'] = select_states($parent_id);
        }
        elseif ($locale == 'cities') 
        {
            $data['response'] = select_cities($parent_id);
        }

        echo json_encode($data, JSON_FORCE_OBJECT);
    }


    /**
     * Fetches notifications for the currently logged in user 
     * @return NULL     Echoes a json string containing the notifications
     */
    public function fetch_notifications()
    {
        header('Content-type: application/json');  

        $response['status']  = 400; 
        $response['message'] = 
        $response['html']    =
        $response['notif']   = FALSE;

        if ($this->uid) 
        {
            try 
            {
                $param         = ['recipient_id' => $this->logged_user['uid'], 'type' => 'all'];
                $notif_list    = $this->notifications->getNotifications($param); 
                $data['notif'] = o2Array($notif_list);  

                $response['status'] = 200;
                $response['html']   = $this->load->view($this->h_theme. '/extra_layout/notifications', $data, TRUE);
            }
            catch(Exception $e)
            {
                $response['status']  = 304;
                $response['message'] = $e;
            }
        }

        echo json_encode($response);
    }


    /**
     * Fetches and updates status of notifications, requests and messages
     * @return NULL     Echoes a json string containing the notifications
     */
    public function update_data()
    {
        header('Content-type: application/json'); 

        $response['status']       = 400; 
        $response['notif']        =
        $response['new_messages'] =
        $response['chats']        =
        $response['requests']     = FALSE;

        if ($this->uid) 
        {
            $features = $this->input->get(NULL, TRUE);

            try 
            {
                $param                    = ['recipient_id' => $this->logged_user['uid'], 'type' => 'new'];
                $notifications            = $this->notifications->getNotifications($param);
                if ($notifications) {
                    $response['notif']    = $notifications;
                }
                $response['requests']     = 0;
                $response['new_messages'] = 0;

                $response['status']       = 200; 
            }
            catch(Exception $e)
            {
                $response['status']  = 304;
                $response['message'] = $e;
            }
        }

        echo json_encode($response);
    }  


    /**
     * Change the state of the card 
     * @return NULL     Echoes a json string containing the new state
     */
    public function change_card_state()
    { 
        $post      = $this->input->post(NULL, TRUE);
        $cur_state = $this->db->select('state')
            ->where('page', $post['page'])->where('uid', $this->uid)->where('card_id', $post['id'])
            ->get('card_state')->row_array(); 
        
        $new_state = ($cur_state['state'] !== $post['state'] ? $post['state'] : '');

        if ($cur_state) 
        {
            $this->db->where('page', $post['page'])->where('uid', $this->uid)->where('card_id', $post['id'])
            ->update('card_state', array('state' => $new_state));
        } 
        else 
        {
            $this->db->insert('card_state', 
                array(
                    'page' => $post['page'], 
                    'uid' => $this->uid, 
                    'card_id' => $post['id'], 
                    'state' => $post['state']
                )
            );
        }

        echo ($cur_state['state'] !== $post['state'] ? true : false);
    }


    /**
     * Validate Multiple Users 
     * @return NULL     Echoes a json string containing the new state
     */
    public function validate_mutiple_accounts()
    { 
        header('Content-type: application/json'); 
        $response['status'] = 400;

        $response['error']['password'] =  
        $response['error']['email']    =  
        $response['error']['phone']    = 'Invalid Request';

        if (has_privilege('manage-employee')) 
        {
            if ($post = $this->input->post()) 
            { 
                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
                $this->form_validation->set_rules('password', lang('password'), 'trim|required|min_length[8]');
                $this->form_validation->set_rules('email', lang('email_address'), 'callback_match_server_url['.$post['email'].']|valid_email|trim|required|is_unique[users.email]');
                $this->form_validation->set_rules('phone', lang('phone'), 'trim|numeric|required|min_length[6]|regex_match[/^[0-9]{11}$/]|is_unique[users.phone]');  

                if ($this->form_validation->run() === FALSE) 
                {  
                    $response['error']['password'] = form_error('password');
                    $response['error']['email']    = form_error('email');
                    $response['error']['phone']    = form_error('phone');
                } 
                else 
                {
                    $response['status'] = 200;
                }
            } 
        }
        echo json_encode($response);
    }

    public function match_server_url($email, $email_str)
    {   
        $email = array_reverse(explode('@', $email)); 
        array_pop($email); 
        $email_server = implode('@', $email);

        $email_str = str_ireplace('@' . my_config('primary_server'), '', $email_str);
        if ($this->db->where(['username' => $email_str])->count_all_results('users')) {
            $this->form_validation->set_message('match_server_url', '\'' . $email_str . '@' . my_config('primary_server').'\' is not available!');
            return FALSE;
        }
        elseif (filter_var(config_item('protocol') . $email_server, FILTER_VALIDATE_URL) && $email_server !== my_config('primary_server'))
        {
            $this->form_validation->set_message('match_server_url', '{field} server must be \'' . my_config('primary_server').'\'.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}
