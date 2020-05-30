<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Licenser {

    function __construct() {
        $this->CI = & get_instance();
    }

	public function generate($quantity = 0, $action = null, $validity = 365)
	{	  

		if ($quantity == 0) {
			$quantity = 1;
		}
		$keys = [];
		for ($i=1; $i <= $quantity; $i++) { 
			$keys[] .=  $this->CI->aes->generate_license();
		} 
		if ($action == 'save') {
			foreach ($keys as $key) {
				$data = array(
					'key' => $key,
					'valid_for' => $validity
				);
				$this->CI->license_model->add($data);
			}
		}
		$strtotime = 'now +'.$validity.' days';
		$done = ($action == 'save' ? ' generated and saved' : ' generated');
		$api = array(
			'status' => 1, // Success
			'message' => count($keys).' keys were'.$done.', valid for '.timespan(time(), strtotime($strtotime), 2), 
			'response' => $keys
		);
		return $api;
	}

}
