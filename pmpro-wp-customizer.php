<?php
/**
 * Plugin Name: PMPro WP Customizer
 * Plugin URI: http://testlab.sample.com/wiki/
 * Description: TAdd WordPress Customizer options to an installation with Paid Memberships Pro.
 * Version: 0.1.1
 * Author: pbrocks
 * Author URI: http://testlab.sample.com/wiki/
 */

namespace PMPro_WP_Customizer;

/**
 * Description
 *
 * @return type Words
 */
require_once( 'autoload.php' );
require_once( __DIR__ . '/pmpro-register-helper.php' );
inc\classes\PMPro_WP_Customizer::init();

/**
 * Enqueue scripts for the customizer pane/controls/previewer.
 */
function customize_controls_enqueue_scripts() {
	$handle = 'pmpro-customize-example-pane';
	wp_enqueue_script(
		$handle,
		plugin_dir_url( __FILE__ ) . 'pane.js',
		array( 'customize-controls' )
	);
}
// add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\customize_controls_enqueue_scripts' );
/**
 * Handle initialization of customizer preview.
 */
function customize_preview_init() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wp_enqueue_scripts' );
}
// add_action( 'customize_preview_init', __NAMESPACE__ . '\customize_preview_init' );
/**
 * Enqueue scripts for the customizer preview.
 */
function wp_enqueue_scripts() {
	$handle = 'pmpro-customize-example-preview';
	wp_enqueue_script(
		$handle,
		plugin_dir_url( __FILE__ ) . 'preview.js',
		array( 'customize-preview' )
	);
}
