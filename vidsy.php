<?php
/*
Plugin Name: Vidsy.tv
Plugin URI: https://wordpress.org/plugins/vidsy/
Description: WordPress integration with Vidsy.tv
Version: 1.0.5.2
Author: Vidsy.tv
Author URI: http://vidsy.tv
License: GPL2+
*/
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;
//Definimos algunos globales
define('VIDSY_URL', 'http://vidsy.tv');
define('VIDSY_DOMAIN', 'vidsy.tv');
define('VIDSY_PLUGINPATH', plugin_dir_path(__FILE__));
define('VIDSY_PLUGINURL', plugin_dir_url(__FILE__));
$upload_dir = wp_upload_dir();
define('VIDSY_PLUGINUPLOADS', $upload_dir['basedir'] . '/vidsytv');
define('VIDSY_DBVERSION', '1.0.0');
//Admin
if (is_admin()) {
				include_once (VIDSY_PLUGINPATH . 'admin/admin.php');
				include_once (VIDSY_PLUGINPATH . 'admin/editorbutton.php');
}
//Widgets
include_once (VIDSY_PLUGINPATH . 'widgets/recentvideos.php');
include_once (VIDSY_PLUGINPATH . 'widgets/recentplaylist.php');
include_once (VIDSY_PLUGINPATH . 'widgets/playerrecent.php');
include_once (VIDSY_PLUGINPATH . 'widgets/playerplaylist.php');
include_once (VIDSY_PLUGINPATH . 'widgets/playerrecentplaylists.php');
//Shortcodes
include_once (VIDSY_PLUGINPATH . 'includes/shortcodes.php');
