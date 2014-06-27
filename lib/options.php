<?php

class ScrollUpOptions {

	public function plugin_add_options() {
		add_options_page('Scroll Up Options', 'Scroll Up Options', 'manage_options', 'scrollupoptions', array(&$this, 'plugin_options_page'));
	}

	function plugin_options_page() {

		$opt_name = array(
		    'scrollup_text' => 'scrollup_text',
		    'scrollup_type' => 'scrollup_type',
		    'scrollup_show' => 'scrollup_show',
		    'scrollup_mobile' => 'scrollup_mobile',
		    'scrollup_position' => 'scrollup_position',
		    'scrollup_distance' => 'scrollup_distance',
		    'scrollup_animation' => 'scrollup_animation',
		    'scrollup_attr' => 'scrollup_attr',
		);
		$hidden_field_name = 'scrollup_submit_hidden';

		$opt_val = array(
		    'scrollup_text' => get_option($opt_name['scrollup_text']),
		    'scrollup_type' => get_option($opt_name['scrollup_type']),
		    'scrollup_show' => get_option($opt_name['scrollup_show']),
		    'scrollup_mobile' => get_option($opt_name['scrollup_mobile']),
		    'scrollup_position' => get_option($opt_name['scrollup_position']),
		    'scrollup_distance' => get_option($opt_name['scrollup_distance']),
		    'scrollup_animation' => get_option($opt_name['scrollup_animation']),
		    'scrollup_attr' => get_option($opt_name['scrollup_attr'])
		);

		if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
			$opt_val = array(
			    'scrollup_text' => stripslashes(esc_html(esc_attr(($_POST[$opt_name['scrollup_text']])))),
			    'scrollup_type' => $_POST[$opt_name['scrollup_type']],
			    'scrollup_show' => $_POST[$opt_name['scrollup_show']],
			    'scrollup_mobile' => $_POST[$opt_name['scrollup_mobile']],
			    'scrollup_position' => $_POST[$opt_name['scrollup_position']],
			    'scrollup_distance' => stripslashes(esc_html(esc_attr(($_POST[$opt_name['scrollup_distance']])))),
			    'scrollup_animation' => $_POST[$opt_name['scrollup_animation']],
			    'scrollup_attr' => stripslashes(esc_html(esc_attr(($_POST[$opt_name['scrollup_attr']]))))
			);
			update_option($opt_name['scrollup_text'], $opt_val['scrollup_text']);
			update_option($opt_name['scrollup_type'], $opt_val['scrollup_type']);
			update_option($opt_name['scrollup_show'], $opt_val['scrollup_show']);
			update_option($opt_name['scrollup_mobile'], $opt_val['scrollup_mobile']);
			update_option($opt_name['scrollup_position'], $opt_val['scrollup_position']);
			update_option($opt_name['scrollup_distance'], $opt_val['scrollup_distance']);
			update_option($opt_name['scrollup_animation'], $opt_val['scrollup_animation']);
			update_option($opt_name['scrollup_attr'], $opt_val['scrollup_attr']);
			?>
			<div id="message" class="updated fade">
				<p><strong>
						<?php _e('Options saved.', 'scroll-up-locale'); ?>
					</strong></p>
			</div>
			<?php
		}
		?>

		<div class="wrap">
			<h2><?php _e('Scroll Up Options', 'att_trans_domain'); ?></h2>
			<form name="att_img_options" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

				<p><label for=""><?php _e('Text', 'att_trans_domain'); ?>:</label>
					<input type="text" name="<?php echo $opt_name['scrollup_text']; ?>" id="<?php echo $opt_name['scrollup_text']; ?>" value="<?php echo $opt_val['scrollup_text']; ?>"/>
				</p>

				<p><label for=""><?php _e('Type', 'att_trans_domain'); ?>:</label>
					<select name="<?php echo $opt_name['scrollup_type']; ?>">
						<option value="link" <?php echo ($opt_val['scrollup_type'] == "link") ? 'selected="selected"' : ''; ?> ><?php _e('Text link', 'scroll-up-locale'); ?></option>
						<option value="pill" <?php echo ($opt_val['scrollup_type'] == "pill") ? 'selected="selected"' : ''; ?> ><?php _e('Pill', 'scroll-up-locale'); ?></option>
						<option value="tab" <?php echo ($opt_val['scrollup_type'] == "tab") ? 'selected="selected"' : ''; ?> ><?php _e('Tab', 'scroll-up-locale'); ?></option>
					</select>
				</p>

				<p><label for=""><?php _e('Position', 'att_trans_domain'); ?>:</label>
					<select name="<?php echo $opt_name['scrollup_position']; ?>">
						<option value="left" <?php echo ($opt_val['scrollup_position'] == "left") ? 'selected="selected"' : ''; ?> ><?php _e('Left', 'scroll-up-locale'); ?></option>
						<option value="right" <?php echo ($opt_val['scrollup_position'] == "right") ? 'selected="selected"' : ''; ?> ><?php _e('Right', 'scroll-up-locale'); ?></option>
						<option value="center" <?php echo ($opt_val['scrollup_position'] == "center") ? 'selected="selected"' : ''; ?> ><?php _e('Center', 'scroll-up-locale'); ?></option>
					</select>
				</p>

				<p><label for=""><?php _e('Show in homepage', 'att_trans_domain'); ?>:</label>
					<select name="<?php echo $opt_name['scrollup_show']; ?>">
						<option value="0" <?php echo ($opt_val['scrollup_show'] == "0") ? 'selected="selected"' : ''; ?> ><?php _e('No', 'scroll-up-locale'); ?></option>
						<option value="1" <?php echo ($opt_val['scrollup_show'] == "1") ? 'selected="selected"' : ''; ?> ><?php _e('Yes', 'scroll-up-locale'); ?></option>
					</select>
				</p>

				<p><label for=""><?php _e('Show in mobile devices', 'att_trans_domain'); ?>:</label>
					<select name="<?php echo $opt_name['scrollup_mobile']; ?>">
						<option value="0" <?php echo ($opt_val['scrollup_mobile'] == "0") ? 'selected="selected"' : ''; ?> ><?php _e('No', 'scroll-up-locale'); ?></option>
						<option value="1" <?php echo ($opt_val['scrollup_mobile'] == "1") ? 'selected="selected"' : ''; ?> ><?php _e('Yes', 'scroll-up-locale'); ?></option>
					</select>
				</p>

				<p><label for=""><?php _e('Distance from top before showing scroll up element', 'att_trans_domain'); ?>:</label>
					<input type="text" name="<?php echo $opt_name['scrollup_distance']; ?>" id="<?php echo $opt_name['scrollup_distance']; ?>" value="<?php echo $opt_val['scrollup_distance']; ?>"/>px
				</p>

				<p><label for=""><?php _e('Show animation', 'att_trans_domain'); ?>:</label>
					<select name="<?php echo $opt_name['scrollup_animation']; ?>">
						<option value="fade" <?php echo ($opt_val['scrollup_animation'] == "fade") ? 'selected="selected"' : ''; ?> ><?php _e('Fade', 'scroll-up-locale'); ?></option>
						<option value="slide" <?php echo ($opt_val['scrollup_animation'] == "slide") ? 'selected="selected"' : ''; ?> ><?php _e('Slide', 'scroll-up-locale'); ?></option>
						<option value="none" <?php echo ($opt_val['scrollup_animation'] == "none") ? 'selected="selected"' : ''; ?> ><?php _e('None', 'scroll-up-locale'); ?></option>
					</select>
				</p>

				<p><label for=""><?php _e('Onclick event', 'att_trans_domain'); ?>:</label>
					<input type="text" name="<?php echo $opt_name['scrollup_attr']; ?>" id="<?php echo $opt_name['scrollup_attr']; ?>" value="<?php echo $opt_val['scrollup_attr']; ?>"/>
					<span style="font-size:11px;font-style:italic;">example: type <code>exit()</code> in order to add an event <code>onclick="exit()"</code></span>
				</p>

				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'scroll-up-locale'); ?>"></p>
			</form>

			<?php
		}

	}
	