<?php

/*
  Plugin Name: Smooth Scroll Up
  Plugin URI: http://wordpress.org/extend/plugins/smooth-scroll-up/
  Author URI: http://www.kouratoras.gr
  Author: Konstantinos Kouratoras
  Contributors: kouratoras
  Tags: page, scroll up, scroll, up, navigation, back to top, back, to, top, scroll to top
  Requires at least: 3.2
  Tested up to: 3.9
  Stable tag: 0.7
  Version: 0.7
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

define('SMTH_SCRL_UP_PLUGIN_DIR', 'smooth-scroll-up');

class ScrollUp {

	private $detector;
	private $inHome;

	/* -------------------------------------------------- */
	/* Constructor
	  /*-------------------------------------------------- */

	public function __construct() {

		//Text domain
		load_plugin_textdomain('scroll-up-locale', false, plugin_dir_path(__FILE__) . '/lang/');

		//Mobile detection library
		require_once( plugin_dir_path(__FILE__) . '/lib/Mobile_Detect.php' );
		$this->detector = new Mobile_Detect;

		//Options Page
		require_once( plugin_dir_path(__FILE__) . '/lib/options.php' );
		$myScrollUpOptions = new ScrollUpOptions();
		add_action('admin_menu', array(&$myScrollUpOptions, 'plugin_add_options'));

		if (!(get_option('scrollup_mobile', '0') == 0 && ($this->detector->isMobile() || $this->detector->isTablet()))) {
			//Register scripts and styles
			add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_scripts'));
			add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_styles'));

			//Action links
			add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);

			//Inline CSS
			add_action('wp_head', array(&$this, 'plugin_css'));

			//Start up script
			add_action('wp_footer', array(&$this, 'plugin_js'));
		}
	}

	public function plugin_action_links($links, $file) {
		static $current_plugin;

		if (!$current_plugin) {
			$current_plugin = plugin_basename(__FILE__);
		}

		if ($file == $current_plugin) {
			$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=scrollupoptions">' . __('Settings', 'scroll-up-locale') . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	function plugin_css() {

		$scrollup_position = get_option('scrollup_position', 'left');
		if ($scrollup_position == 'center')
			echo '<style>#scrollUp {left: 47%;}</style>';
		else if ($scrollup_position == 'right')
			echo '<style>#scrollUp {right: 20px;}</style>';
		else
			echo '<style>#scrollUp {left: 20px;}</style>';
	}

	function plugin_js() {
		$scrollup_show = get_option('scrollup_show', '0');

		if ($scrollup_show == "1" || $scrollup_show == "0" && (!is_home() || !is_front_page())) {
			$scrollup_text = str_replace("&#039;", "\'", html_entity_decode(get_option('scrollup_text', 'Scroll to top')));

			$scrollup_distance = str_replace("&#039;", "\'", html_entity_decode(get_option('scrollup_distance', '')));
			$scrollup_distance = ($scrollup_distance != '' ? $scrollup_distance : '300');

			$scrollup_animation = get_option('scrollup_animation', 'fade');

			$scrollup_attr = str_replace("&#039;", "\'", html_entity_decode(get_option('scrollup_attr', '')));

			echo '<script> var $nocnflct = jQuery.noConflict();
			$nocnflct(function () {
			    $nocnflct.scrollUp({
				scrollName: \'scrollUp\', // Element ID
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

		wp_register_script('scrollup', plugins_url(SMTH_SCRL_UP_PLUGIN_DIR . '/js/jquery.scrollUp.min.js'), '', '', true);
		wp_enqueue_script('scrollup');
	}

	/* -------------------------------------------------- */
	/* Registers and enqueues styles.
	  /* -------------------------------------------------- */

	public function register_plugin_styles() {

		$scrollup_type = get_option('scrollup_type', 'tab');

		if ($scrollup_type == 'link') {
			wp_register_style('link', plugins_url(SMTH_SCRL_UP_PLUGIN_DIR . '/css/link.css'));
			wp_enqueue_style('link');
		} else if ($scrollup_type == 'pill') {
			wp_register_style('pill', plugins_url(SMTH_SCRL_UP_PLUGIN_DIR . '/css/pill.css'));
			wp_enqueue_style('pill');
		} else {
			wp_register_style('tab', plugins_url(SMTH_SCRL_UP_PLUGIN_DIR . '/css/tab.css'));
			wp_enqueue_style('tab');
		}
	}

}

new ScrollUp();
