<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class My_config {

    function __construct() {
        $this->CI = & get_instance();
    }

    public function item($item, $index = '')
    {   
        $config = $this->CI->setting_model->get_settings($item);
        if ($index == '')
        {
            return $config ? $config : NULL;
        }

        return isset($config[$index], $config[$index][$item]) ? $config[$index][$item] : NULL;
    }

    public function load_items()
    {   
        $configuration = $this->CI->setting_model->get_settings();
        $config        = array();
        foreach ($configuration as $key => $value) 
        {
            if ($this->CI->config->item($value['setting_key']) !== NULL) 
            {
                $config[$value['setting_key']] = $this->CI->config->item($value['setting_key']);
            } 
            else 
            {
                $config[$value['setting_key']] = $value['setting_value'];
            }
        }

        return isset($config) ? $config : NULL;
    }

	public function alert($msg = '', $type = 'info')
	{	
		$icon = '';
		if ($type == 'danger') {
			$title = 'Error!';
			$icon = 'ban';
		} elseif ($type == 'warning') {
			$title = 'Warning!';
			$icon = 'exclamation-triangle';
		} elseif ($type == 'info') {
			$title = 'Notice';
			$icon = 'info';
		} elseif ($type == 'success') {
			$title = 'Success';
			$icon = 'check';
		}
		if ($msg != '') {
			$alert = 
			'<div class="alert alert-'.$type.' alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-'.$icon.'"></i> '.$title.'</h4>
				'.$msg.'.
			</div>';
			return $alert;
		}
		return;
	}

    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
    }
    
    public function timeDifference($date_1, $date_2)
    {
    	$datetime1 = strtotime($date_1);
        $datetime2 = strtotime($date_2);
        $interval = abs($datetime2 - $datetime1) / 3600;

        return $interval;
    }

}
