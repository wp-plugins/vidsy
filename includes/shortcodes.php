<?php
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;
/**
 * [vidsy] shortcode
 * @param array $atts
 *        The array contains the following keys:
 *        width, in pixels or percentage. Default: 100%.
 *        height, in pixels or percentage. Default: 800px.
 *        type, one of the following: fullsite, playerrecent. Default: fullsite.
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
								'theme' => 'light' //light, dark
				) , $atts));

				$output = '';

				if(stripos($width, '%') !== false) {
					$width = str_ireplace('%', '%25', $width);
				}

				if(stripos($width, 'px') !== false) {
					$width = str_ireplace('px', '', $width);
				}

				if(stripos($height, '%') !== false) {
					$height = str_ireplace('%', '%25', $height);
				}

				if(stripos($height, 'px') !== false) {
					$height = str_ireplace('px', '', $height);
				}

				$userdata  = get_option('vidsy_options');
				$subdomain = $userdata['subdomain'];
				$userdata  = $userdata['userdata'];

				if (empty($userdata->userid) OR empty($subdomain)) {
					$output .= '<p>Please configure your plugin first.</p>';
				} else {
					if($type == 'fullsite') {
						$output.= '<script type="text/javascript" src="//vidsy.tv/public/widgets/fullsite.js?userid='. $userdata->userid . '&amp;username=' . $subdomain . '&amp;width=' . $width . '&amp;height=' . $height . '"></script>';
					} elseif ($type == 'playerrecent') {
						$output.= '<script type="text/javascript" src="//vidsy.tv/public/widgets/playerrecent.js?userid='. $userdata->userid . '&amp;username=' . $subdomain . '&amp;width=' . $width . '&amp;height=' . $height . '&theme=' . $theme . '"></script>';
					} else {
						$output .= '<p>Error A113. Please contact us at hello (at) vidsy.tv</p>';
					}
				}

				return $output;
}
add_shortcode('vidsy', 'shortcode_vidsy');
