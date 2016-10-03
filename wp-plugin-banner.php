<?php
/*
 Plugin Name: WP Plugin Banner
 Plugin URI: https://chrisk.io
 Description: Adds a shortcode to display a banner from a plugin in the WordPress repository
 Author: cklosows
 Version: 0.1
 Author URI: https://chrisk.io
 Text Domain: wp-plugin-banner
 Domain Path: languages
 */

if ( ! class_exists( 'WP_Plugin_Banner' ) ) {
class WP_Plugin_Banner {
	private static $instance;

	private function __construct() {
		$this->init();
	}

	static public function instance() {

		if ( !self::$instance ) {
			self::$instance = new WP_Plugin_Banner();
		}

		return self::$instance;

	}

	private function init() {
		add_shortcode( 'plugin_banner', array( $this, 'display_banner' ) );
	}

	public function display_banner( $atts ) {
		$atts = shortcode_atts(
			array( 'slug' => '', 'link' => false ),
			$atts,
			'wp_plugin_banners_display_atts'
		);
		
		if ( empty( $atts['slug'] ) ) {
			return;
		}

		$image_url = 'https://plugins.svn.wordpress.org/' . $atts['slug'] . '/assets/banner-772x250.png';
		$link_url  = $atts['link'] ? 'https://wordpress.org/plugins/' . $slug : '';

		$image_test = wp_remote_head( $image_url );
		$image_exists = ! is_wp_error( $image_test ) && 200 == $image_test['response']['code'] ? true : false;

		if ( ! $image_exists ) {
			return;
		};

		ob_start();
		if ( ! empty( $link_url ) ) {
			?><a class="wp-plugin-banner-link <?php echo $atts['slug']; ?>" href="<?php echo $link_url; ?>"><?php
		}

		?><img class="wp-plugin-banner <?php echo $atts['slug']; ?>" src="<?php echo $image_url; ?>" /><?php

		if ( ! empty( $link_url ) ) {
			?></a><?php
		}

		ob_end_flush();
	}
}
}

function load_wp_plugin_banner() {
	return WP_Plugin_Banner::instance();
}
add_action( 'plugins_loaded', 'load_wp_plugin_banner', PHP_INT_MAX );
