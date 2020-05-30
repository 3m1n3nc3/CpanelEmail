<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| This files sets config items from database
|--------------------------------------------------------------------------
|
|
*/

$CI = & get_instance();

$config = $CI->my_config->load_items();
