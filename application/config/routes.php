<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/ 

$route['default_controller']             = "admin/dashboard";
$route['page/(:any)']                    = "homepage/page/$1";

$route['account/login']                  = "homepage/access";
$route['account/login/(:any)']           = "homepage/access/$1";
$route['account']                        = "homepage/account";
$route['account/(:any)']                 = "homepage/account/$1";
$route['account/(:any)/(:any)']          = "homepage/account/$1/$2";

$route['reservations']                   = "homepage/reservations";
$route['reservations/(:any)']            = "homepage/reservations/$1";
$route['reservations/(:any)/(:any)']     = "homepage/reservations/$1/$2";
$route['review/invoice/(:any)']          = "homepage/invoice/$1";
$route['generate/invoice/(:any)/(:any)'] = "homepage/generic_invoice/$1/$2";

$route['my-payments']                    = "homepage/payments";
$route['my-payments/(:any)']             = "homepage/payments/$1";

// For this route, the id as the first parameter is routed as the second parameter
$route['page/rooms/(:any)']        = "homepage/rooms/$1";
$route['page/rooms/(:any)/(:any)'] = "homepage/rooms/$2/$1/";
// ----------------------------------------------------------------------------------

$route['dashboard']                = "admin/dashboard/";
$route['dashboard/(:any)']         = "admin/$1";

$route['room-type']                = "room_type"; 
$route['room-type/(:any)']         = "room_type/$1";
$route['room-type/(:any)/(:any)']  = "room_type/$1/$2";

$route['admin']                    = "admin/configuration";

$route['accounting']               = "accounting/cashier";

$route['customer']                 = "customer/list";

$route['service']                  = "services";

$route['404_override']             = 'errors/page404'; 

 
