<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "auth";
$route['404_override'] = '';

/*
 * Authentication Routes
 */
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

/*
 * Inventory Routes
 */
$route['purchase_orders'] = 'inventory/purchase_orders';
$route['goods_receipts'] = 'inventory/goods_receipts';
$route['adjustments'] = 'inventory/adjustments';

/*
 * Human-Resource Routes
 */
$route['expenses'] = 'payroll_extra/expenses';
$route['social_contributions'] = 'payroll_extra/social_contributions';

/* End of file routes.php */
/* Location: ./application/config/routes.php */