<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "auth";
$route['404_override'] = '';

/*
 * Authentication Routes
 */
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

/*
 * Master Modules Routes
 */
$route['dashboard'] = 'masters/dashboard';
$route['procurement'] = 'masters/procurement';
$route['sales'] = 'masters/sales';
$route['production'] = 'masters/production';
$route['distributions'] = 'masters/distributions';
$route['human_resource'] = 'masters/human_resource';
$route['settings'] = 'masters/settings';

/*
 * Inventory Routes
 */
$route['purchase_orders'] = 'inventory/purchase_orders';
$route['goods_receipts'] = 'inventory/goods_receipts';
$route['adjustments'] = 'inventory/adjustments';

/*
 * Human-Resource Routes
 */
//$route['bonuses'] = 'payroll_extra/index';
$route['expenses'] = 'payroll_extra/expenses';
$route['social_contributions'] = 'payroll_extra/social_contributions';

/* End of file routes.php */
/* Location: ./application/config/routes.php */