<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
|
|
*/

$config['attributes'] = array('class' => 'page-link');

$config['full_tag_open']    = '<ul class="d-flex pagination justify-content-center mx-auto">';
$config['full_tag_close']   = '</ul>';

$config['per_page']         = 10;
$config['notifs_per_page']  = 10;
$config['use_page_numbers'] = TRUE;
$config['reuse_query_string'] = FALSE;

$config['first_link']       = '<i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i>'; 
$config['first_tag_open']    = '<li class="page-item ml-1 text-info">';
$config['first_tag_close']   = '</li>';

$config['last_link']        = '<i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i>'; 
$config['last_tag_open']    = '<li class="page-item ml-1 text-info">';
$config['last_tag_close']   = '</li>';

$config['next_link']        = '<i class="fa fa-chevron-right"></i>'; 
$config['next_tag_open']    = '<li class="page-item ml-1 text-info">';
$config['next_tag_close']   = '</li>';

$config['prev_link']        = '<i class="fa fa-chevron-left"></i>'; 
$config['prev_tag_open']    = '<li class="page-item mr-1 text-info">';
$config['prev_tag_close']   = '</li>';

$config['cur_tag_open']     = '<li class="page-item active mx-1 text-info"><a href="#" class="page-link">';
$config['cur_tag_close']    = '</a></li>';

$config['num_tag_open']     = '<li class="page-item mx-1 text-info">';
$config['num_tag_close']    = '</li>';
