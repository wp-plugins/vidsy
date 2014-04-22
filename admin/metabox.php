<?php
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;

/*
http://codex.wordpress.org/Function_Reference/add_meta_box
http://themefoundation.com/wordpress-meta-boxes-guide/
http://www.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/
http://pinakibisi.wordpress.com/2013/06/21/how-to-add-a-metabox-to-a-custom-post-type/
 */
function vidsymeta_init()
{
				$metabox = new vidsyMetaboxAutoInsert();
}

if (is_admin()) {
				add_action('load-post.php', 'vidsymeta_init');
				add_action('load-post-new.php', 'vidsymeta_init');
}

class vidsyMetaboxAutoInsert
{
				/**
				 * Hook into the appropriate actions when the class is constructed.
				 */
				public function __construct()
				{
								add_action('add_meta_boxes', array(
												$this,
												'add_meta_box'
								));
								add_action('save_post', array(
												$this,
												'save'
								));
				}
				/**
				 * Adds the meta box container.
				 */
				public function add_meta_box($post_type)
				{
								$post_types = array(
												'post',
												'page'
								); //limit meta box to certain post types
								if (in_array($post_type, $post_types)) {
												add_meta_box('vidsytv_metabox', __('Related Videos', 'vidsytv') , array(
																$this,
																'render_meta_box_content'
												) , $post_type, 'side', 'default');
								}
				}
				/**
				 * Save the meta when the post is saved.
				 *
				 * @param int $post_id The ID of the post being saved.
				 */
				public function save($post_id)
				{
								/*
								 * We need to verify this came from the our screen and with proper authorization,
								 * because save_post can be triggered at other times.
								*/
								// Check if our nonce is set.
								if (!isset($_POST['vidsytv_inner_custom_box_nonce'])) return $post_id;

								$nonce = $_POST['vidsytv_inner_custom_box_nonce'];
								// Verify that the nonce is valid.
								if (!wp_verify_nonce($nonce, 'vidsytv_inner_custom_box')) return $post_id;
								// If this is an autosave, our form has not been submitted,
								//     so we don't want to do anything.
								if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
								// Check the user's permissions.
								if ('page' == $_POST['post_type']) {
												if (!current_user_can('edit_page', $post_id)) return $post_id;
								} else {
												if (!current_user_can('edit_post', $post_id)) return $post_id;
								}
								/* OK, its safe for us to save the data now. */
								// Sanitize the user input.
								$vmeta_auto_enable   = sanitize_text_field($_POST['vidsytv_auto_enabled']);
								$vmeta_auto_playlist = sanitize_text_field($_POST['vidsytv_auto_playlist']);
								$vmeta_auto_theme    = sanitize_text_field($_POST['vidsytv_auto_theme']);
								// Update the meta field.
								update_post_meta($post_id, '_vidsytv_auto_enabled', $vmeta_auto_enable);
								update_post_meta($post_id, '_vidsytv_auto_playlist', $vmeta_auto_playlist);
								update_post_meta($post_id, '_vidsytv_auto_theme', $vmeta_auto_theme);
				}
				/**
				 * Render Meta Box content.
				 *
				 * @param WP_Post $post The post object.
				 */
				public function render_meta_box_content($post)
				{
								// Add an nonce field so we can check for it later.
								wp_nonce_field('vidsytv_inner_custom_box', 'vidsytv_inner_custom_box_nonce');
								// Use get_post_meta to retrieve an existing values from the database.
								$vmeta_auto_enable   = get_post_meta($post->ID, '_vidsytv_auto_enabled', true);
								$vmeta_auto_playlist = get_post_meta($post->ID, '_vidsytv_auto_playlist', true);
								$vmeta_auto_theme    = get_post_meta($post->ID, '_vidsytv_auto_theme', true);
								// Display the form, using the current values.
								?>
								<p>
									Auto insert related videos from your Vidsy.tv collection, after the post content.
								</p>
								<p>
									<input type="checkbox" name="vidsytv_auto_enabled" id="vidsytv_auto_enabled" value="yes" <?php if (isset($vmeta_auto_enable)) checked($vmeta_auto_enable, 'yes'); ?> /> <label for="vidsytv_auto_enabled">Enable</label>
								</p>
								<p>
									<strong>Select the source for your related videos</strong>
								</p>
								<p>
						<?php
								$vidsy_options = get_option('vidsy_options', '');
								$vidsy_subdomain = $vidsy_options['subdomain'];
								$error = true;
								/*
												Traemos los resultados desde un trasient (cache 60 minutos), si no, y solo si no existen, vamos a la API para volver a consultar.
								*/
								if (false === ($playlists = get_transient('vidsy_playlists_' . $vidsy_subdomain))) {
												$apiresponse = wp_remote_get(VIDSY_URL . '/api/playlists/for/' . $vidsy_subdomain, array(
																'timeout' => 15
												));
												if (is_wp_error($apiresponse) || !isset($apiresponse['body'])) {
																$error = true;
												} else {
																$apiresults = json_decode(wp_remote_retrieve_body($apiresponse));
																if ($apiresults->status == 'error') {
																				$error = true;
																} else {
																				$error = false;
																				$playlists = $apiresults->playlists;
																}
												}

												if ($error === false) {
																set_transient('vidsy_playlists_' . $vidsy_subdomain, $playlists, 60 * 60);
												}
								} else {
												$error = false;
								}
								if (empty($vidsy_subdomain)) {
												$error === true;
								}

								if ($error === true) {
?>
										Error while connecting with Vidsy's servers. Please reload this page and try again.
									<?php
								} else {
?>
							<select id="vidsytv_auto_playlist" name="vidsytv_auto_playlist">
									<option value="recent" <?php if (isset($vmeta_auto_playlist)) selected($vmeta_auto_playlist, 'recent'); ?>>All Recent Videos</option>

								<?php
												foreach ($playlists as $pl) {
																$pl->name = wp_kses($pl->name, array());
																$pl->id = (int)$pl->id;
																$value_name = strip_tags($pl->name);
																$value_nameapi = esc_attr(strtolower(preg_replace('!\s+!', ' ', $value_name)));
?>
									<option value="<?php
																echo $value_nameapi; ?>" <?php if (isset($vmeta_auto_playlist)) selected($vmeta_auto_playlist, $value_nameapi); ?>><?php
																echo $value_name; ?></option>

									<?php
												} ?>
							</select>

									<?php
								}
?>
								</p>
								<p>
									<strong>Background</strong>
								</p>
								<p>
									<select name="vidsytv_auto_theme" id="vidsytv_auto_theme">
											<option value="light" <?php if (isset($vmeta_auto_theme)) selected($vmeta_auto_theme, 'light'); ?>>Light</option>
											<option value="dark" <?php if (isset($vmeta_auto_theme)) selected($vmeta_auto_theme, 'dark'); ?>>Dark</option>
									</select>
								</p>
								<?php
				}
}
