<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curler {

    public $CI;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
    }

    public function fetch($url, $post = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $data = curl_exec($ch);
        $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $data_merge = json_decode($data, true);
        $data_merge1 = array('http_code' => $resultStatus);

        if ($data_merge && $data_merge1) {
            $data = json_encode(array_merge($data_merge, $data_merge1), JSON_FORCE_OBJECT);
        }
        
        $data = json_decode($data);
        return $data;
    }

    public function ssl_fetch($url, $post = array())
    {
        $ch = curl_init();   
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $data = curl_exec($ch);
        $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $data = array('http_code' => $resultStatus, 'curl_error' => curl_error($ch));
        } else {
        
            $data_merge = json_decode($data, true);
            $data_merge1 = array('http_code' => $resultStatus);

            if ($data_merge && $data_merge1) {
                $data = json_encode(array_merge($data_merge, $data_merge1), JSON_FORCE_OBJECT);
            }

        }

        $data = json_decode($data);    
        return $data;
    }

}
