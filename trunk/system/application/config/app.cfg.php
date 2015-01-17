<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CONFIGURATION ARRAY FOR THE FreakAuth_light library
 *
 * @package     SIMIJIN
 * @subpackage  Config
 * @category    Authentication
 * @author      Banu Araidi
 * @copyright   Copyright (c) 2010, Solusiti
 * @license     http://www.gnu.org/licenses/lgpl.html
 * @link        http://
 * @version     1.0
 */

/*---------------------+
|  MAIN CONFIGURATION  |
+---------------------*/
/*
 |--------------------------------------------------------------------------
 | Name of the website
 |--------------------------------------------------------------------------
 |
 | It will be displayed in some headers and in the subject of the emails
 |
 */ 
$config['aplication_name']    = "eGov - SIMPATDA";

$config['theme_dir']    = "themes/brown";
$config['layout_dir']   = "layout/brown";
$config['template_dir'] = $config['layout_dir'].'/template';
$config['theme_menu']   = 'menu_joomla';


/*
 |--------------------------------------------------------------------------
 | email address of the administrator
 |--------------------------------------------------------------------------
 |
 | It will be displayed in some headers and in the subject of the emails
 |
 */
$config['user_support']    = "admin@mail.com";

/*
 |--------------------------------------------------------------------------
 | Enable/Disable FreakAuth light system
 |--------------------------------------------------------------------------
 |
 | the "turned off" template will be displayed if FALSE
 |
 */
 $config['active_site'] = TRUE;
 
 /*
 |------------------------------------------------------------------------------
 | The table prefix for the database tables needed by ...
 |------------------------------------------------------------------------------
 */
  $config['FAL_table_prefix'] = 'fa_';
?>