<?php
/*
Plugin Name: Vidsy.tv
Plugin URI: http://vidsy.tv
Description: WordPress integration with Vidsy.tv
Version: 1.0.0
Author: Vidsy.tv
Author URI: http://vidsy.tv
*/
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;
//Path y URL del plugin
$vidsy['pluginpath']            = plugin_dir_path(__FILE__);
$vidsy['pluginurl']            = plugin_dir_url(__FILE__);
$upload_dir = wp_upload_dir();
$vidsy['pluginupload']            = $upload_dir['basedir'] . '/vidsytv';
$vidsy['dbversion']            = '1.0.0';
//Creamos nuestro item en el menu

function vidsy_settingsmenu()
{
				global $vidsy;
				add_menu_page('Vidsy.tv', 'Vidsy.tv', 'activate_plugins', 'vidsy-admin', 'vidsy_admin_pagestart', $vidsy['pluginurl'] . '/images/vidsylogoonly_16.png');
				add_submenu_page('vidsy-admin', 'Configuration', 'Plugin Configuration', 'activate_plugins', 'vidsy-admin-config', 'vidsy_admin_config');
				//Un pequeño truco, pagina vacia
				add_submenu_page(null, 'TCattd VTV', 'TCattd VTV', 'activate_plugins', 'vidsy-admin-show', 'vidsy_admin_show');
}
//add_action('admin_menu', 'vidsy_settingsmenu');


/**
 * Template: menu superior paginas
 * @param  string $arg Titulo de la pagina a mostrar
 * @return html output
 */

function vidsy_menusuperior($arg   = '')
{
				global $title, $vidsy;

				if (is_array($arg) AND $arg['title']) $title = $arg['title'];
?>
	<h2><?php
				echo esc_html($title); ?></h2>
	<?php
}
/**
 * Pagina: configuracion del plugin
 * @return html output
 */

function vidsy_admin_config()
{
				global $vidsy;
?>
	<div class="wrap">
		<?php
				vidsy_menusuperior(); ?>
		<p>Placeholder.</p>
	</div>
	<?php
}
/**
 * Pagina: vista principal
 * @return html output
 */

function vidsy_admin_pagestart()
{
				global $vidsy;
?>
	<div class="wrap">
		<?php
				vidsy_menusuperior(); ?>
		<p>Placeholder.</p>
	</div>
	<?php
}
/* Widgets */
include ($vidsy['pluginpath'] . 'widgets/recentvideos.php');
