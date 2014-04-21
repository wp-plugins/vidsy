<?php
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;

function vidsy_thickbox_content()
{
				global $pagenow;

				if (is_admin() AND ('post.php' == $pagenow OR 'post-new.php' == $pagenow)) {
?>
				<div id="thickbox_vidsyshortcode_window" style="display: none;">
					<div class="">
					<div class="vidsy_shortc_mainexplain">Please configure how you want to insert your Vidsy Widget.</div>
							<table class="form-table vidsy-form-table">
								<tbody>
									<tr valign="top">
										<th scope="row">Type</th>
										<td>
											<select name="vshortc_type" id="vidsyshortcode_type">
													<option value="playerrecent">Player with recent videos</option>
													<option value="playerplaylist">Player with single playlist videos</option>
													<option value="playerrecentandplaylists">Player with recent videos and all playlists</option>
													<option value="fullsite">Fullsite</option>
												</select>
											<p class="description">Select widget type to embed</p>
										</td>
									</tr>
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
?>
									<tr valign="top">
										<th scope="row">Playlist</th>
										<td>
<?php
								if ($error === true) {
?>
										Error while connecting with Vidsy's servers. Please reload this page and try again.
									<?php
								} else {
?>
							<select id="vshortc_playlist" name="vshortc_playlist" disabled="disabled">
								<?php
												foreach ($playlists as $pl) {
																$pl->name = wp_kses($pl->name, array());
																$pl->id = (int)$pl->id;
																$value_name = strip_tags($pl->name);
																$value_nameapi = esc_attr(strtolower(preg_replace('!\s+!', ' ', $value_name)));
?>
									<option value="<?php
																echo $value_nameapi; ?>"><?php
																echo $value_name; ?></option>

									<?php
												} ?>
							</select>

									<?php
								}
?>

											<p class="description">Select a playlist. Only for single playlist player.</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">Width</th>
										<td>
											<input name="vshortc_width" id="vshortc_width" type="text" size="4" value="100" /> <input type="radio" name="vshortc_width_perorpix" value="%" checked="checked" class="vshortc_width_perorpix" />% <input type="radio" name="vshortc_width_perorpix" value="px" class="vshortc_width_perorpix" />px
											<p class="description">Width of your embed. 100% is recommended</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">Height</th>
										<td>
											<input name="vshortc_height" id="vshortc_height" type="text" size="4" value="400" /> <input type="radio" name="vshortc_height_perorpix" value="%" class="vshortc_height_perorpix" />% <input type="radio" name="vshortc_height_perorpix" value="px" checked="checked" class="vshortc_height_perorpix" />px
											<p class="description">Height of your embed. Pixels ir recommended</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">Theme</th>
										<td>
											<select name="vshortc_theme" id="vidsyshortcode_theme">
													<option value="light">Light</option>
													<option value="dark">Dark</option>
											</select>
											<p class="description">Select your theme</p>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"></th>
										<td>
											<a href="#" id="vshortc_addbutton" class="button button-primary button-large">Add Shortcode</a> <a href="#" id="vshortc_closebutton" class="button button-large">Cancel</a>
										</td>
									</tr>
								</tbody>
							</table>
					</div>
				</div>
				<?php
				}
}
add_action('admin_footer', 'vidsy_thickbox_content');
// init process for registering our button
function wpse_vidsy_shortcode_button_init()
{
				//Abort early if the user will never see TinyMCE
				if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {
								return;
				}
				//Add a callback to regiser our tinymce plugin
				add_filter("mce_external_plugins", "wpse_vidsy_register_tinymce_plugin");
				// Add a callback to add our button to the TinyMCE toolbar
				add_filter('mce_buttons', 'wpse_vidsy_add_tinymce_button');
}
add_action('init', 'wpse_vidsy_shortcode_button_init');
//This callback registers our plug-in
function wpse_vidsy_register_tinymce_plugin($plugin_array)
{
				$plugin_array['wpse_vidsy_button'] = VIDSY_PLUGINURL . 'js/admin-button-shortcode.js';
				return $plugin_array;
}
//This callback adds our button to the toolbar
function wpse_vidsy_add_tinymce_button($buttons)
{
				//Add the button ID to the $button array
				$buttons[] = "wpse_vidsy_button";
				return $buttons;
}
//Javascript modal shortcode
function vidsy_modal_shortcode_js($hook)
{
				if ('post.php' != $hook AND 'post-new.php' != $hook) {
								return;
				}

				wp_enqueue_script('vidsy_modalshortcode', VIDSY_PLUGINURL . 'js/admin-modal-shortcode.js');
				wp_enqueue_style('vidsy_modalshortcodecss', VIDSY_PLUGINURL . 'css/admin-modal-shortcode.css');
}
add_action('admin_enqueue_scripts', 'vidsy_modal_shortcode_js');
