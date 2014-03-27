<?php
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;
/**
 * [vidsy] shortcode
 * @param array $atts
 *        The array contains the following keys:
 *        width, in pixels or percentage. Default: 100%.
 *        height, in pixels or percentage. Default: 800px.
 *        type, one of the following: fullsite, playerrecent, playerplaylist, playerrecentandplaylists. Default: fullsite.
 *        theme, one of the following: light, dark. Default: light.
 * @return string
 *         Shortcode HTML output
 */
function shortcode_vidsy($atts)
{
				extract(shortcode_atts(array(
								'width' => '100%',
								'height' => '800',
								'type' => 'fullsite',
								'theme' => 'light', //light, dark
								'playlist' => ''
				) , $atts));

				$output = '';

				if (stripos($width, '%') !== false) {
								$width = str_ireplace('%', '%25', $width);
				}

				if (stripos($width, 'px') !== false) {
								$width = str_ireplace('px', '', $width);
				}

				if (stripos($height, '%') !== false) {
								$height = str_ireplace('%', '%25', $height);
				}

				if (stripos($height, 'px') !== false) {
								$height = str_ireplace('px', '', $height);
				}

				$playlist = wp_kses($playlist, array());
				$playlist = trim(preg_replace('/\s+/', ' ', $playlist));
				$playlist = str_ireplace(' ', '+', $playlist);
				$playlistid = '';
				$playlisttransient = md5($playlist);

				$userdata = get_option('vidsy_options');
				$subdomain = $userdata['subdomain'];
				$userdata = $userdata['userdata'];

				if (empty($userdata->userid) OR empty($subdomain) OR ($type == 'playerplaylist') AND empty($playlist)) {
								$output.= '<div class="vidsyerror">Please configure your plugin first.</div>';
				} else {
								if ($type == 'fullsite') {
												$output.= '<script type="text/javascript" src="//vidsy.tv/public/widgets/fullsite.js?userid=' . $userdata->userid . '&amp;username=' . $subdomain . '&amp;width=' . $width . '&amp;height=' . $height . '"></script>';
								} elseif ($type == 'playerplaylist') {
												$error = true;
												/*
												Traemos los resultados desde un trasient (cache 60 minutos), si no, y solo si no existen, vamos a la API para volver a consultar.
												*/
												if (false === ($playlistid = get_transient('vidsy_' . $playlisttransient))) {
																$apiresponse = wp_remote_get(VIDSY_URL . '/api/playlists/fromname/' . $playlist . '/for/' . $subdomain, array('timeout' => 15));
																if (is_wp_error($apiresponse) || !isset($apiresponse['body'])) {
																				$error = true;
																} else {
																				$apiresults = json_decode(wp_remote_retrieve_body($apiresponse));
																				if ($apiresults->status == 'error') {
																								$error = true;
																				} else {
																								$error = false;
																								$playlistid = $apiresults->playlist->id;
																				}
																}

																if ($error === false) {
																				set_transient('vidsy_' . $playlisttransient, $playlistid, 60 * 60);
																}
												} else {
																$error = false;
												}

												if ($error === true) {
																$output.= '<div class="vidsyerror">Error while connecting with Vidsy\'s servers. Please reload this page and try again.</div>';
												} else {
																$output.= '<script type="text/javascript" src="//vidsy.tv/public/widgets/playerplaylist.js?userid=' . $userdata->userid . '&amp;username=' . $subdomain . '&amp;playlistid=' . $playlistid . '&amp;width=' . $width . '&amp;height=' . $height . '&amp;theme=' . $theme . '"></script>';
												}
								} elseif ($type == 'playerrecent') {
												$output.= '<script type="text/javascript" src="//vidsy.tv/public/widgets/playerrecent.js?userid=' . $userdata->userid . '&amp;username=' . $subdomain . '&amp;width=' . $width . '&amp;height=' . $height . '&amp;theme=' . $theme . '"></script>';
								} elseif($type == 'playerrecentandplaylists') {
												$output.= '<script type="text/javascript" src="//vidsy.tv/public/widgets/playerrecentplaylists.js?userid=' . $userdata->userid . '&amp;username=' . $subdomain . '&amp;width=' . $width . '&amp;height=' . $height . '&amp;theme=' . $theme . '"></script>';
								} else {
												$output.= '<div class="vidsyerror">Error A113. Please contact us at hello (at) vidsy.tv</div>';
								}
				}

				return $output;
}
add_shortcode('vidsy', 'shortcode_vidsy');
