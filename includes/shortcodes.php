<?php
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;
/**
 * [vidsy]
 * @param  width, pixels or percentage
 * @param  height, pixels or percentage
 * @return html output
 */
function shortcode_vidsy($atts)
{
				extract(shortcode_atts(array(
								'width' => '100%',
								'height' => '800',
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
					$output.= '<script type="text/javascript" src="//vidsy.tv/public/widgets/fullsite.js?userid='. $userdata->userid . '&amp;username=' . $subdomain . '&amp;width=' . $width . '&amp;height=' . $height . '"></script>';
				}

				return $output;
}
add_shortcode('vidsy', 'shortcode_vidsy');
