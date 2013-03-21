<?php
/*
  Plugin Name: Smooth Scroll Up
  Plugin URI: http://wordpress.org/extend/plugins/smooth-scroll-up/
  Author URI: http://www.kouratoras.gr
  Author: Konstantinos Kouratoras
  Contributors: kouratoras
  Tags: page, scroll up, scroll, up, navigation, back to top
  Requires at least: 2.9.0
  Tested up to: 3.5.1
  Stable tag: 0.1
  Version: 0.1
  License: GPLv2 or later
  Description: Scroll Up plugin lightweight plugin that creates a customizable "Scroll to top" feature in any post/page of your WordPress website.

  Copyright 2012 Konstantinos Kouratoras (kouratoras@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

define('PLUGIN_DIR_NAME', 'smooth-scroll-up');

class ScrollUp {
	/* -------------------------------------------------- */
	/* Constructor
	  /*-------------------------------------------------- */

	public function __construct() {

		load_plugin_textdomain('scroll-up-locale', false, plugin_dir_path(__FILE__) . '/lang/');

		//Register scripts and styles
		add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_scripts'));
		add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_styles'));

		//Options Page
		add_action('admin_menu', array(&$this, 'plugin_add_options'));
	}

	public function plugin_add_options() {
		add_options_page('Scroll Up Options', 'Scroll Up Options', 8, 'scrollupoptions', array(&$this, 'plugin_options_page'));
	}

	function plugin_options_page() {

		$opt_name = array('scrollup_type' => 'scrollup_type');
		$hidden_field_name = 'scrollup_submit_hidden';

		$opt_val = array('scrollup_type' => get_option($opt_name['scrollup_type']));

		if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
			$opt_val = array('scrollup_type' => $_POST[$opt_name['scrollup_type']]);
			update_option($opt_name['scrollup_type'], $opt_val['scrollup_type']);
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
			<h2><?php _e('Smooth Scroll Up', 'att_trans_domain'); ?></h2>
			<form name="att_img_options" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
				<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

				<p><label for="">Scroll Up Type</label>
					<select name="<?php echo $opt_name['scrollup_type']; ?>">
						<option value="link" <?php echo ($opt_val['scrollup_type'] == "link") ? 'selected="selected"' : ''; ?> ><?php _e('Text link', 'scroll-up-locale'); ?></option>
						<option value="pill" <?php echo ($opt_val['scrollup_type'] == "pill") ? 'selected="selected"' : ''; ?> ><?php _e('Pill', 'scroll-up-locale'); ?></option>
						<option value="tab" <?php echo ($opt_val['scrollup_type'] == "tab") ? 'selected="selected"' : ''; ?> ><?php _e('Tab', 'scroll-up-locale'); ?></option>
					</select>
				</p>

				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'scroll-up-locale'); ?>"></p>
			</form>

			<?php
		}

		/* -------------------------------------------------- */
		/* Registers and enqueues scripts.
		  /* -------------------------------------------------- */

		public function register_plugin_scripts() {

			wp_enqueue_script('jquery');

			wp_register_script('scrollup', plugins_url(PLUGIN_DIR_NAME . '/js/jquery.scrollUp.min.js'), '', '', true);
			wp_enqueue_script('scrollup');

			wp_register_script('scrollupscript', plugins_url(PLUGIN_DIR_NAME . '/js/jquery.scrollUpScript.js'), '', '', true);
			wp_enqueue_script('scrollupscript');
		}

		/* -------------------------------------------------- */
		/* Registers and enqueues styles.
		  /* -------------------------------------------------- */

		public function register_plugin_styles() {

			$scrollup_type = get_option('scrollup_type', 'tab');

			if ($scrollup_type == 'link') {
				wp_register_style('link', plugins_url(PLUGIN_DIR_NAME . '/css/link.css'));
				wp_enqueue_style('link');
			}

			if ($scrollup_type == 'pill') {
				wp_register_style('pill', plugins_url(PLUGIN_DIR_NAME . '/css/pill.css'));
				wp_enqueue_style('pill');
			}

			if ($scrollup_type == 'tab') {
				wp_register_style('tab', plugins_url(PLUGIN_DIR_NAME . '/css/tab.css'));
				wp_enqueue_style('tab');
			}
		}

	}

	new ScrollUp();