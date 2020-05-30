<?php 


class Notifications {
	public $type = null;

	public $notifier_id = null;

	public $types = array(
		'made_services_sales' => array(
			'icon' => 'fa fa-store', 
			'text' => 'made_services_sales'
		),
		'accept_request' => array(
			'icon' => 'user-plus', 
			'text' => 'accept_request'
		),
		'liked_ur_post' => array(
			'icon' => 'thumbs-up',
			'text' => 'liked_ur_post'
		) 
	);

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
        $this->CI->config->load('pagination');
    }

    /** 
     *
     * Sends a notification to the specified user
     *
     * @param   array      $data 	[recipient_id]: The id of the receiver of the notification 
     *                            	[notifier_id]: The id of the sender of the notification 
     *                            	[type]: The type of the notification
     * @return  null
     */
	public function notify($data = array()){
		global $config;
	    if (empty($data) || !is_array($data)) 
        {
	        return false;
	    }
	    
	    $this->CI->db->where('notifier_id', $data['notifier_id']);
	    $this->CI->db->where('recipient_id', $data['recipient_id']);
	    $this->CI->db->where('type', $data['type']);
        if (!empty($data['notifier_type'])) 
        {
            $this->CI->db->where('notifier_type', $data['notifier_type']);
        }
		$this->CI->db->delete('notifications');

        $data['text']          = !empty($data['text']) ? $data['text'] : ''; 
	    $data['notifier_type'] = !empty($data['notifier_type']) ? $data['notifier_type'] : ''; 
	    $query                 = $this->CI->db->insert('notifications',$data); 
	    return $query;
	}


    /** 
     *
     * Checks if the notification to view requires a session to be set and sets it
     * @param   object      $notifs 	An Object or array containing the notifications
     * @return  null
     */
	function setNotificationSession($notifs = array()) {
		if ($notifs) 
		{
			foreach ($notifs as $key => $notif) 
			{  
				$explode = explode('.', $notif->type);
				if (count($explode)===4) 
				{
					$notifs[$key]->type = $explode[0];
					
					if ($explode[1] === 'ss') 
					{
						$e_key = $explode[3]; 
						$_SESSION[$explode[2]] = $notif->$e_key;  
					}
				}
			}
		}
		return $notifs;
	}


    /** 
     *
     * Gets all the notifications for the specified user
     *
     * @param   array      $query 	[recipient_id]: The logged in user or receiver of the notification 
     *                            	[type]: Possible values [new,all]
     * @return  null
     */
	function getNotifications($param = array(), $offset = false)
	{
		if (empty($param['recipient_id']))
		{
			return false;
		}
 
		$user_id = $param['recipient_id'];
		$limit   = 10;
		$data    = array();
		$update  = array();
		
	    $this->CI->db->where('recipient_id', $user_id);

	    if ($param['type'] == 'new') 
	    {
	        $d = $this->CI->db->select('COUNT(*) AS count')->where('seen', 0)->get('notifications')->row();
	        $data = $d->count;
	    }
	    else
	    { 
            $this->CI->db->where('notifier_type', 'user');
            $this->CI->db->or_where('notifier_type', NULL);
            $this->CI->db->select('n.*, u.username, u.image');
            $this->CI->db->join("users u","n.notifier_id = u.uid ","INNER"); 

	    	if (!empty($offset))
	    	{
	    		$this->CI->db->limit($this->config->item('notifs_per_page'), $offset); 
	    	}

	    	$this->CI->db->order_by('id', 'DESC');
	        $query = $this->CI->db->get("notifications n")->result();

	        if (!empty($query)) 
	        {
	        	foreach ($query as $notif_data) 
	        	{ 
	        		$notif_data->image = $notif_data->image;
		            $data[] = $notif_data;
		            $update[] = $notif_data->id;
		        }

		        $this->CI->db->where_in('id', $update)->update('notifications', array('seen' => time()));
	        }

	    	$data = $this->setNotificationSession($data); 
	    }

	    return $data;
	}


    private function setNotifType($type = '')
    {
        $this->type = $type;
        return $this;
    }

    private function verifyPrivilege($list_types)
    {
        $list_types = trim($list_types); 
        $list_types = str_ireplace(' ', '', $list_types); 
        $list_types = explode(',', $list_types); 
        if (in_array($this->type, $list_types)) 
        {
            return true;
        }
    }


    /** 
     *
     * Checks if a moderator has the required privilege
     *
     * @param   array      $data 	type: The type of notification to check for permission
     * @return  null
     */
	public function notifyPrivilege($type = 'made_services_sales', $user_data = array())
	{ 
        self::setNotifType($type);

		if (self::verifyPrivilege('made_services_sales')) 
		{
			$privilege = 'sales-services';
		} 
        elseif (self::verifyPrivilege('customer_paid_reservation, overstayed_reservation, made_reservation'))
        {
            $privilege = 'reservation'; 
        }
        elseif (self::verifyPrivilege('added_new_customer')) 
        {
            $privilege = 'customers'; 
        }
        elseif (self::verifyPrivilege('cleared_a_debt')) 
        {
            $privilege = 'payments'; 
        }
        elseif (self::verifyPrivilege('added_inventory_item, updated_inventory_item')) 
        {
            $privilege = 'inventory'; 
        }

        if (!empty($privilege) && has_privilege($privilege, o2Array($user_data))) 
        {
        	return true;
        }
	}


    /** 
     *
     * Sends the notification to all moderators with the required privilege
     *
     * @param   array      $data 	[type]: The type of notification to send   
     *                            	[url]: The url for the notification
     * @return  null
     */
	public function notifyPrivilegedMods($data = array()){
		if (!$this->CI->account_data->logged_in() || empty($data['url'])) {
			// return false;
		}

		$users   = $this->CI->user_model->get_users();
        $user_id = $this->CI->logged_user['uid']??'';

        if (!empty($data['uid'])) 
        {
            $user_id = $data['uid'];
        }

		foreach ($users as $user) {
			try { 
				$privileged_uid = $user->uid;
				$notif_conf     = null;

				if (is_numeric($privileged_uid)) {
					$notif_conf = $this->notifyPrivilege($data['type'], $user);
				}

				if ($privileged_uid && ($privileged_uid != $uid || !empty($this->uid)) && $notif_conf) {
					$re_data = array(
						'notifier_id' => $user_id,
						'recipient_id' => $privileged_uid,
						'type' => $data['type'],
						'url' => $data['url'],
						'time' => time()
					);

                    if (isset($data['notifier_type'])) 
                    {
                        $re_data['notifier_type'] = $data['notifier_type'];
                    }
					
					$this->notify($re_data);
				}
			} 
			catch (Exception $e) {
				
			}
		}
	}


    public function clearNotifications($data = array())
    { 
        if (empty($data['recipient_id']))
        {
            return false;
        } 

        if (!empty($data['notifier_id']) && is_numeric($data['notifier_id'])) 
        {
            $this->CI->db->where('notifier_id', $data['notifier_id']);
            $this->CI->db->where('recipient_id', $data['recipient_id']);    
        }
        else
        {
            $this->CI->db->where('recipient_id', $data['recipient_id']);
            $this->CI->db->where('time', (time() - 432000));
            $this->CI->db->where('seen >', 0);
        }
        return $this->CI->db->delete('notifications');
    }  
}
