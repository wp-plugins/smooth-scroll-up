<?php

/*
  Plugin Name: Smooth Scroll Up
  Plugin URI: http://wordpress.org/extend/plugins/smooth-scroll-up/
  Author URI: http://www.kouratoras.gr
  Author: Konstantinos Kouratoras
  Contributors: kouratoras
  Tags: page, scroll up, scroll, up, navigation, back to top, back, to, top, scroll to top
  Requires at least: 3.2
  Tested up to: 4.0
  Stable tag: 0.8.2
  Version: 0.8.2
  License: GPLv2 or later
  Description: Smooth Scroll Up is a lightweight plugin that creates a customizable "Scroll to top" feature in any post/page of your WordPress website.

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

define( 'SMTH_SCRL_UP_PLUGIN_DIR', 'smooth-scroll-up' );
define( 'SMTH_SCRL_UP_PLUGIN_NAME', 'Smooth Scroll Up' );

class ScrollUp {

	private $detector;
	private $inHome;

	/* -------------------------------------------------- */
	/* Constructor
	  /*-------------------------------------------------- */

	public function __construct() {
		
		//Load localisation files
		load_plugin_textdomain('scrollup',false,dirname( plugin_basename( __FILE__ ) ) . '/languages');

		//Mobile detection library
		if(!class_exists('Mobile_Detect')){
			require_once( plugin_dir_path(__FILE__) . '/lib/Mobile_Detect.php' );
		}		
		$this->detector = new Mobile_Detect;

		//Options Page
		require_once( plugin_dir_path(__FILE__) . '/lib/options.php' );
		$myScrollUpOptions = new ScrollUpOptions();
		add_action('admin_menu', array(&$myScrollUpOptions, 'plugin_add_options'));

		if (!(get_option('scrollup_mobile', '0') == 0 && ($this->detector->isMobile() || $this->detector->isIphone()))) {
			
			//Register scripts and styles
			add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_scripts'));
			add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_styles'));

			//Action links
			add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);

			//Start up script
			add_action('wp_footer', array(&$this, 'plugin_js'));
		}
	}

	public function plugin_action_links($links, $file) {
		static $current_plugin = '';

		if (!$current_plugin) {
			$current_plugin = plugin_basename(__FILE__);
		}

		if ($file == $current_plugin) {
			$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=scrollupoptions">' . __('Settings', 'scrollup') . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}
	
	function plugin_js() {
		$scrollup_show = get_option('scrollup_show', '0');

		if ($scrollup_show == "1" || $scrollup_show == "0" && (!is_home() || !is_front_page())) {
			
			//Fetch options
			$scrollup_type = get_option('scrollup_type', 'tab');
			$scrollup_position = get_option('scrollup_position', 'left');
			$scrollup_text = str_replace("&#039;", "\'", html_entity_decode(get_option('scrollup_text', 'Scroll to top')));
			$scrollup_distance = (str_replace("&#039;", "\'", html_entity_decode(get_option('scrollup_distance', ''))) != '' ? str_replace("&#039;", "\'", html_entity_decode(get_option('scrollup_distance', ''))) : '300');
			$scrollup_animation = get_option('scrollup_animation', 'fade');
			$scrollup_attr = str_replace("&#039;", "\'", html_entity_decode(get_option('scrollup_attr', '')));
			
			//Scroll up type class
			$scrollup_type_class = 'scrollup-tab';
			if ($scrollup_type == 'link') {
				$scrollup_type_class = 'scrollup-link';
			}
			else if ($scrollup_type == 'pill') {
				$scrollup_type_class = 'scrollup-pill';
			}
			else if ($scrollup_type == 'image') {
				$scrollup_type_class = 'scrollup-image';
				$scrollup_text = "";
			}
			else {
				$scrollup_type_class = 'scrollup-tab';
			}

			//Scroll up position class
			$scrollup_position_class = 'scrollup-left';
			if ($scrollup_position == 'center') {
				$scrollup_position_class = 'scrollup-center';
			}
			else if ($scrollup_position == 'right') {
				$scrollup_position_class = 'scrollup-right';
			}
			else {
				$scrollup_position_class = 'scrollup-left';
			}

			//Creation script
			echo '<script> var $nocnflct = jQuery.noConflict();
			$nocnflct(function () {
			    $nocnflct.scrollUp({
				scrollName: \'scrollUp\', // Element ID
				scrollClass: \'scrollUp '.$scrollup_type_class.' '.$scrollup_position_class.'\', // Element Class
				scrollDistance: ' . $scrollup_distance . ', // Distance from top/bottom before showing element (px)
				scrollFrom: \'top\', // top or bottom
				scrollSpeed: 300, // Speed back to top (ms)
				easingType: \'linear\', // Scroll to top easing (see http://easings.net/)
				animation: \'' . $scrollup_animation . '\', // Fade, slide, none
				animationInSpeed: 200, // Animation in speed (ms)
				animationOutSpeed: 200, // Animation out speed (ms)
				scrollText: \'' . $scrollup_text . '\', // Text for element, can contain HTML
				scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
				scrollImg: false, // Set true to use image
				activeOverlay: false, // Set CSS color to display scrollUp active point
				zIndex: 2147483647 // Z-Index for the overlay
			    });
			});';

			//Onclick function
			if ($scrollup_attr != '')
				echo '
				$nocnflct( document ).ready(function() {   
					$nocnflct(\'#scrollUp\').attr(\'onclick\', \'' . $scrollup_attr . '\');
				});
				';

			echo '</script>';
		}
	}

	/* -------------------------------------------------- */
	/* Registers and enqueues scripts.
	  /* -------------------------------------------------- */

	public function register_plugin_scripts() {

		wp_enqueue_script('jquery');

		wp_register_script('scrollup-js', plugins_url(SMTH_SCRL_UP_PLUGIN_DIR . '/js/jquery.scrollUp.min.js'), '', '', true);
		wp_enqueue_script('scrollup-js');
	}

	/* -------------------------------------------------- */
	/* Registers and enqueues styles.
	  /* -------------------------------------------------- */

	public function register_plugin_styles() {

		wp_register_style('scrollup-css', plugins_url(SMTH_SCRL_UP_PLUGIN_DIR . '/css/scrollup.css'));
		wp_enqueue_style('scrollup-css');
	}

}

new ScrollUp();
