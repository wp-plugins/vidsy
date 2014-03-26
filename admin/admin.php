<?php
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;

class VidsySettingsPage
{
				/**
				 * Mantiene las variables a usar en los callbacks de los campos
				 */
				private $options;
				/**
				 * Inicio
				 */
				public function __construct()
				{
								add_action('admin_menu', array(
												$this,
												'add_plugin_page'
								));
								add_action('admin_init', array(
												$this,
												'page_init'
								));
				}
				/**
				 * Paginas de opciones
				 */
				public function add_plugin_page()
				{
								add_menu_page('Vidsy Dashboard', 'Vidsy.tv', 'manage_options', 'vidsy-admin', array(&$this,
												'vidsy_page_start'
								) , VIDSY_PLUGINURL . '/images/vidsylogoonly_16.png');
								add_submenu_page('vidsy-admin', 'Vidsy Configuration', 'Settings', 'manage_options', 'vidsy-config', array(&$this,
												'vidsy_page_config'
								));
								//Un pequeño truco, pagina vacia
								add_submenu_page(null, 'VTV', 'VTV', 'manage_options', 'vidsy-admin-blank', array(&$this,
												'vidsy_page_blank'
								));
				}
				/**
				 * Pagina: vista principal
				 */
				public function vidsy_page_start()
				{
?>
			<div class="wrap">
				<h2>Vidsy Dashboard</h2>
				<?php
								$vidsy_options = get_option('vidsy_options', '');
								$vidsy_subdomain = $vidsy_options['subdomain'];

								if (empty($vidsy_subdomain)) {
?>
						<p>Please, <a href="<?php
												echo admin_url('admin.php?page=vidsy-config'); ?>">configure</a> your Vidsy subdomain first.</p>
					<?php
								} else {
?>
						<iframe src="http://<?php
												echo VIDSY_DOMAIN; ?>/dashboard/?wordpress=true" style="width: 100%; height: 80%; min-height: 800px; overflow-x: hidden; overflow-y: auto;" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

					<?php
								}
?>
			</div>
			<?php
				}
				/**
				 * Pagina: opciones
				 */
				public function vidsy_page_config()
				{
								// Set class property
								$this->options = get_option('vidsy_options');
?>
				<div class="wrap">
						<h2>Vidsy Settings</h2>
						<form method="post" action="options.php">
						<?php
								// This prints out all hidden setting fields
								settings_fields('vidsy_option_group');
								do_settings_sections('vidsy-config');
								submit_button();
?>
						</form>
				</div>
				<?php
				}
				/**
				 * Pagina: en blanco
				 */
				public function vidsy_page_blank()
				{
				}
				/**
				 * Register and add settings
				 */
				public function page_init()
				{
								register_setting('vidsy_option_group', // Option group
								'vidsy_options', // Option name
								array(
												$this,
												'sanitize'
								) // Sanitize
								);

								add_settings_section('vidsy_main_settings', // ID
								'', // Title
								array(
												$this,
												'print_section_info'
								) , // Callback
								'vidsy-config'
								// Page
								);
								/*add_settings_field('id_number', // ID
								'ID Number', // Title
								array(
												$this,
												'id_number_callback'
								) , // Callback
								'vidsy-config', // Page
								'vidsy_main_settings'
								// Section
								);*/

								add_settings_field('subdomain', 'Your subdomain', array(
												$this,
												'subdomain_callback'
								) , 'vidsy-config', 'vidsy_main_settings');

								add_settings_field('userdata', '', array(
												$this,
												'userdata_callback'
								) , 'vidsy-config', 'vidsy_main_settings');
				}
				/**
				 * Sanitize each setting field as needed
				 *
				 * @param array $input Contains all settings fields as array keys
				 */
				public function sanitize($input)
				{
								$new_input = array();

								if (isset($input['id_number'])) {
												$new_input['id_number'] = absint($input['id_number']);
								}

								if (isset($input['subdomain'])) {
												$new_input['subdomain'] = sanitize_text_field($input['subdomain']);
								}

								if (isset($input['userdata'])) {
												$new_input['userdata'] = '';
								}
								//Check if it's valid/exist at Vidsy
								if (!empty($new_input['subdomain'])) {
												$apiresponse = wp_remote_get(VIDSY_URL . '/api/subdomain/' . $new_input['subdomain']);
												if (is_wp_error($apiresponse) || !isset($apiresponse['body'])) {
																$new_input['subdomain'] = 'api error';
												} else {
																$apiresults = json_decode(wp_remote_retrieve_body($apiresponse));
																if ($apiresults->status == 'error') {
																				$new_input['subdomain'] = 'invalid subdomain (does not exist)';
																} else {
																				$new_input['userdata'] = $apiresults->userdata;
																}
												}
								}

								return $new_input;
				}
				/**
				 * Print the Section text
				 */
				public function print_section_info()
				{
								print '<br />Enter your settings below:';
				}
				/**
				 * Get the settings option array and print one of its values
				 */
				public function id_number_callback()
				{
								printf('<input type="text" id="id_number" name="vidsy_options[id_number]" value="%s" />', isset($this->options['id_number']) ? esc_attr($this->options['id_number']) : '');
				}
				/**
				 * Get the settings option array and print one of its values
				 */
				public function subdomain_callback()
				{
								printf('<input type="text" id="subdomain" name="vidsy_options[subdomain]" value="%s" />', isset($this->options['subdomain']) ? esc_attr($this->options['subdomain']) : '');
?>
								<p class="description">Enter your Vidsy's subdomain. Example: for <strong>videos.vidsy.tv</strong>, your subdomain is: <strong>videos</strong></p>
								<?php
								if (!isset($this->options['subdomain']) OR empty($this->options['subdomain'])) { ?>
								<p class="description">Don't have an account?: <a href="<?php
												echo VIDSY_URL; ?>" target="blank">register one here</a>.</p>
								<?php
								}
				}
				/**
				 * Get the settings option array and print one of its values
				 */
				public function userdata_callback()
				{
								print ('<input type="hidden" id="userdata" name="vidsy_options[userdata]" value="" />');
				}
}

$vidsy_settings_page = new VidsySettingsPage();
