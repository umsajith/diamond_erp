<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Company Global Settings
 */
$config['glCompanySh']       = 'Агро Про ДОО';
$config['glCompanyLn']       = 'Агро Про ДОО Експорт-Импорт Куманово';
$config['glCompanyAddr']     = 'Козјачка бб';
$config['glCompanyCity']     = 'Куманово';
$config['glCompanyZip']      = '1300';
$config['glCompanyPhn1']     = '031 453 905';
$config['glCompanyPhn2']     = '031 453 906';
$config['glCompanyPhn3']     = '031 453 904';
$config['glCompanyFax']      = '031 412 715';
$config['glCompanyEmail']    = 'info@kumanovskikori.mk';
$config['glCompanyWeb']      = 'www.kumanovskikori.mk';
$config['glCompanyLogoPath'] = '/assets/logo.jpg';

/**
 * Global Financial Setting
 */
$config['glCurrSh'] = ' ден.';
$config['glCurrLn'] = ' денар';

/**
 * DECPRICATED CONFIG
 */
$config['G_title']    = 'Diamond ERP';
$config['G_version']  = '2.0.0 Alpha';
$config['G_currency'] = ' ден.';


/////////////////////////////////////////////////////
// DO NOT EDIT BENEATH THIS LINE - UNLESS CERTAIN  //
/////////////////////////////////////////////////////

/**
 * Diamond ERP Global Settings
 */
$config['glAppTitle']   = 'Diamond ERP';
$config['glAppVersion'] = '2.0.2 ALPHA';
$config['glAppEnv']     = 'development';

$availableLanguages     = ['macedonian','english'];
$availableLocales       = ['mk','en'];

$config['glLang']       = in_array(config_item('language'),$availableLanguages) ? config_item('language') : 'macedonian';
$config['glLocale']     = in_array(config_item('glLocale'),$availableLocales) ? config_item('glLocale') : 'mk';;