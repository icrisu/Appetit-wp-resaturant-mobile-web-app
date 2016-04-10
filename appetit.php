<?php
/*
Plugin Name: Appetit - WP Restaurant Mobile Web App
Plugin URI: http://sakuraplugins.com/
Description: WEDX - WP Restaurant Mobile Web App
Author: SakuraPlugins
Version: 1.0.0
Author URI: http://sakuraplugins.com/
*/

define('APPETIT_ADMIN_URI', plugins_url('', __FILE__).'/resources/admin');
define('APPETIT_FRONT_URI', plugins_url('', __FILE__).'/resources/front');

require_once(__DIR__.'/com/sakuraplugins/plugin-core.php');

$core = new AppetitCore();
$core->init();

?>