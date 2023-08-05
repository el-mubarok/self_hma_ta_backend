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
$route['default_controller'] = 'auth';

// auth
$route['api/auth/login']['POST'] = 'api/C_Auth/postLogin';

// user
$route['api/user']['GET'] = 'api/C_User/index';
$route['api/user/total']['GET'] = 'api/C_User/getTotal';
$route['api/user/(:num)']['GET'] = 'api/C_User/getUser/$1';
$route['api/admin/(:num)']['GET'] = 'api/C_User/getUserAdmin/$1';
$route['api/user/add']['POST'] = 'api/C_User/postAddUser';
$route['api/user/edit']['PATCH'] = 'api/C_User/patchUser';
$route['api/user/delete/(:num)']['DELETE'] = 'api/C_User/deleteUser/$1';
$route['api/user/retrieve/(:num)']['PATCH'] = 'api/C_User/retrieveUser/$1';

// user payment
$route['api/payment']['GET'] = 'api/C_Payment/index';
$route['api/payment/(:num)']['GET'] = 'api/C_Payment/getPayment/$1';
$route['api/payment/calculate']['POST'] = 'api/C_Payment/postCalculate';
$route['api/payment/request']['POST'] = 'api/C_Payment/postRequestPayment';
$route['api/payment/status/:any']['GET'] = 'api/C_Payment/getStatus';
$route['api/payment/details/(:any)']['GET'] = 'api/C_Payment/paymentDetailsPaymentId/$1';
$route['api/payment/history/(:any)']['GET'] = 'api/C_Payment/getHistory/$1';
$route['api/payment/simulate/va']['GET'] = 'api/C_Payment/simulatePaymentVa';

// billing session
$route['api/billing/session']['GET'] = 'api/C_Billing/index';
$route['api/billing/session/(:num)']['GET'] = 'api/C_Billing/index/$1';
$route['api/billing/session/create']['POST'] = 'api/C_Billing/postSession';
$route['api/billing/session/edit']['PATCH'] = 'api/C_Billing/patchSession';
// 
$route['api/billing/session/active']['GET'] = 'api/C_Billing/getActiveSession';
$route['api/billing/session/active/(:num)']['GET'] = 'api/C_Billing/getUserActiveSession/$1';
$route['api/billing/session/active/payment']['GET'] = 'api/C_Billing/getUserActivePayment';
// billing session time
$route['api/billing/session/time']['GET'] = 'api/C_Billing/getSessionTime';
$route['api/billing/session/time/create']['POST'] = 'api/C_Billing/postSessionTime';
$route['api/billing/session/time/edit']['PATCH'] = 'api/C_Billing/patchSessionTime';
$route['api/billing/session/time/delete/(:num)']['DELETE'] = 'api/C_Billing/deleteSessionTime/$1';
// 

// report
$route['api/report']['GET'] = 'api/C_Report/getAll';
$route['api/report/session']['GET'] = 'api/C_Report/getPerSession';
$route['api/report/session/(:num)']['GET'] = 'api/C_Report/getPerSession/$1';

// callback
$route['api/callback']['POST'] = 'api/C_Callback/postCallback';
$route['api/callback/va']['POST'] = 'api/C_Callback/postVa';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
