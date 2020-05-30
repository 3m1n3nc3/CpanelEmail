<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ip {

    function __construct() {
        $this->CI = & get_instance();
    }

    public function get()
    {
    	if ( isset($_SERVER['HTTP_CF_CONNECTING_IP']) ) 
    	{
    		$_SERVER['REMOTE_ADDR'] 	= $_SERVER['HTTP_CF_CONNECTING_IP'];
    		$_SERVER['HTTP_CLIENT_IP'] 	= $_SERVER['HTTP_CF_CONNECTING_IP'];
    	} 

    	$client = @$_SERVER['HTTP_CLIENT_IP'];
    	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    	$remote = @$_SERVER['REMOTE_ADDR'];

    	if ( strpos($forward, ',') > 0 ) 
    	{
    		$forward 	= explode(',', $forward);
    		$ip 		= trim($forward[0]);
    	}
    	else 
    	{
	    	if (filter_var($client, FILTER_VALIDATE_IP)) 
	    	{
	    		$ip 	= $client;
	    	} 
	    	elseif (filter_var($forward, FILTER_VALIDATE_IP)) 
	    	{
	    		$ip 	= $forward;
	    	} 
	    	else 
	    	{
	    		$ip 	= $remote;
	    	}
	    }
	    return $ip;
    }

	// Nigeria: '105.112.177.151', California US: '47.252.13.192';
    public function info()
    {
    	$ip = $this->get(); 
    	$geo_plugin = @json_decode(
    		file_get_contents(
    			'http://www.geoplugin.net/json.gp?ip=' . $ip
    		)
    	);
    	if ($geo_plugin) 
    	{
    		$info = array(
		    	'city' 				=> $geo_plugin->geoplugin_city,
		    	'region' 			=> $geo_plugin->geoplugin_region,
		    	'country' 			=> $geo_plugin->geoplugin_countryName,
		    	'country_code' 		=> $geo_plugin->geoplugin_countryCode,
		    	'continent' 		=> $geo_plugin->geoplugin_continentName,
		    	'continent_code' 	=> $geo_plugin->geoplugin_continentCode,
		    	'timezone' 			=> $geo_plugin->geoplugin_timezone,
		    	'currency_code' 	=> $geo_plugin->geoplugin_currencyCode,
		    	'status' 			=> $geo_plugin->geoplugin_status
		    );
	    }
	    else 
	    {
    		$info = array(
		    	'city' => '', 'region' => '', 'country' => '', 'country_code' => '', 'continent' => '', 
		    	'continent_code' => '', 'timezone' 	=> '', 'currency_code' 	=> '', 'status' => ''
		    );
	    }
	    return $info;
    }

    public function save()
    {	
    	$ip = $this->get();
    	if ($ip !== '127.0.0.1') 
    	{
    		$ip_info = $this->info();
    	}

    	$timenow = date('Y-m-d H:m:s', strtotime('NOW'));

    	$city 		= @$ip_info['city'];
    	$region 	= @$ip_info['region'];
    	$country 	= @$ip_info['country'];

        $check_ip = $this->CI->dashboard_model->get_visitors($ip); //  print_r($check_ip);
        $update = $this->CI->my_config->timeDifference($check_ip['last_visit'], $timenow);

        if ($check_ip && $update >= $this->CI->my_config->item('ip_interval')) 
        {
        	$save = array( 
        		'id' => $check_ip['id'], 'ip' => $ip, 'city' => $city, 'region' => $region, 
        		'visits' => ($check_ip['visits']+1), 'country' => $country, 'last_visit' => $timenow 
        	);
        	$this->CI->dashboard_model->save_visitor($save);
        }
        elseif ( !$check_ip ) 
        {
        	$save = array( 
        		'ip' => $ip, 'city' => $city, 'region' => $region, 'country' => $country, 'first_visit' => $timenow, 'last_visit' => $timenow 
        	);
        	$this->CI->dashboard_model->save_visitor($save);
        }
    }

}
