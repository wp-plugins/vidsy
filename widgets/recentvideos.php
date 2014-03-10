<?php
//No permitimos el acceder directamente a este archivo
if (!defined('ABSPATH')) exit;

class VidsyWidgetRecentVideos extends WP_Widget
{
				function VidsyWidgetRecentVideos()
				{
								$widget_ops = array(
												'classname' => 'VidsyWidgetRecentVideos',
												'description' => 'Displays recent videos from your Vidsy account'
								);
								$this->WP_Widget('VidsyWidgetRecentVideos', 'VidsyTV: recent videos', $widget_ops);
				}

				function form($instance)
				{
								$title  = isset($instance['title']) ? esc_attr($instance['title']) : '';
								$domain = isset($instance['domain']) ? esc_attr($instance['domain']) : 'videos';
								$theme  = isset($instance['theme']) ? esc_attr($instance['theme']) : 'light';
								$height = isset($instance['height']) ? absint($instance['height']) : '350';
?>
						<p>
							<label for="<?php
								echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php
								echo $this->get_field_id('title'); ?>" name="<?php
								echo $this->get_field_name('title'); ?>" type="text" value="<?php
								echo $title; ?>" /></label>
						</p>
						<p>
							<label for="<?php
								echo $this->get_field_id('domain'); ?>">Vidsy's Subdomain:
							<input class="widefat" id="<?php
								echo $this->get_field_id('domain'); ?>" name="<?php
								echo $this->get_field_name('domain'); ?>" type="text" value="<?php
								echo $domain; ?>" /></label><br />
							Enter your Vidsy.tv's subdomain.<br/>Example: for <strong>videos.vidsy.tv</strong>, your subdomain is: <strong>videos</strong>
						</p>
						<p>
							<label for="<?php
								echo $this->get_field_id('theme'); ?>">Select Theme:</label>
							<select id="<?php
								echo $this->get_field_id('theme'); ?>" name="<?php
								echo $this->get_field_name('theme'); ?>">
								<option value="light" <?php
								selected($theme, 'light'); ?>>Light</option>
								<option value="dark" <?php
								selected($theme, 'dark'); ?>>Dark</option>
							</select>
						</p>
						<p>
							<label for="<?php
								echo $this->get_field_id('height'); ?>">Widget height:</label>
							<input id="<?php
								echo $this->get_field_id('height'); ?>" name="<?php
								echo $this->get_field_name('height'); ?>" type="text" value="<?php
								echo $height; ?>" size="3"> pixels.
						</p>
<?php
				}

				function update($new_instance, $old_instance)
				{
								$instance = $old_instance;
								//Vidsy API request for user data
								$userdata = json_decode(wp_remote_retrieve_body(wp_remote_get('http://vidsy.tv/api/subdomain/' . $new_instance['domain'])));
								if ($userdata->status == 'error') {
												$new_instance['domain']          = 'invalid subdomain (does not exist)';
												$instance['userdata']          = '';
								} else {
												$instance['userdata']          = $userdata;
								}

								$instance['title']          = strip_tags($new_instance['title']);
								$instance['domain']          = strip_tags($new_instance['domain']);
								$instance['theme']          = strip_tags($new_instance['theme']);
								$instance['height']          = (int)$new_instance['height'];
								return $instance;
				}

				function widget($args, $instance)
				{
								extract($args, EXTR_SKIP);

								echo $before_widget;
								$title    = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
								$domain   = empty($instance['domain']) ? ' ' : apply_filters('widget_title', $instance['domain']);
								$theme    = empty($instance['theme']) ? ' ' : apply_filters('widget_title', $instance['theme']);
								$height   = empty($instance['height']) ? ' ' : apply_filters('widget_title', $instance['height']);
								//Vidsy's user data
								$userdata = $instance['userdata']->userdata;

								if (!empty($title)) {
												echo $before_title . $title . $after_title;
								}

								if ($height < 100) {
												$height = 350;
								}

								if (empty($userdata->userid)) {
?>
									<div class="textwidget">Please configure your widget.</div>
									<?php
								} else {
?>
									<script type='text/javascript' src='//vidsy.tv/public/widgets/recentvertical.js?userid=<?php
												echo $userdata->userid; ?>&amp;username=<?php
												echo $domain; ?>&amp;width=100%25&amp;height=<?php
												echo $height; ?>&amp;theme=<?php
												echo $theme; ?>'></script>
								<?php
								}
								echo $after_widget;
				}
}
add_action('widgets_init', create_function('', 'return register_widget("VidsyWidgetRecentVideos");'));
