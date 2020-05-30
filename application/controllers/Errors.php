<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Errors extends My_Controller 
{ 
    public function __construct() 
    { 
        parent::__construct(); 

        $data = $this->account_data->fetch($this->uid);  
        $this->data = $data;
    } 


    /**
     * Show the 404 error page 
     * @return null                 Does not return anything but uses code igniter's view() method to render the page
     */
    public function page404() 
    { 
        header('HTTP/1.1 404 Page Not Found.', TRUE, 404);

        $data = $this->data ;
        $data['page_title'] = 'Error 404';

        $data['class']   = 'text-warning'; 
        $data['code']   = '404'; 
        $data['error']   = 'Error 404'; 
        $data['title']   = 'Oops! Page Not Found'; 
        $data['message'] = 'The page you requested was not found on this server.'; 

        $data['view_data']  = $data; 
        echo $this->load->view($this->h_theme.'/extra_layout/error_page', $data, TRUE); 
        exit(1);
    } 


    /**
     * Show the 401 error page 
     * @return null                 Does not return anything but uses code igniter's view() method to render the page
     */
    public function page401() 
    { 
        header('HTTP/1.1 401 Unauthorized Access.', TRUE, 401);
        $data = $this->data ;
        $data['page_title'] = 'Error 401';   

        $data['class']   = 'text-danger'; 
        $data['code']   = '401'; 
        $data['error']   = 'Error 401'; 
        $data['title']   = 'Oops! Unauthorized Access'; 
        $data['message'] = 'You do not have access to the resource you have requested.'; 

        $data['view_data']  = $data; 
        echo $this->load->view($this->h_theme.'/extra_layout/error_page', $data, TRUE); 
        exit(1);
    } 
}
