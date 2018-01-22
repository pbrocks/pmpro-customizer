<?php

namespace PMPro_WP_Customizer\inc\classes;

defined( 'ABSPATH' ) || die( 'File cannot be accessed directly' );

/**
 * An example of how to write code to PEAR's standards
 *
 * Docblock comments start with "/**" at the top.  Notice how the "/"
 * lines up with the normal indenting and the asterisks on subsequent rows
 * are in line with the first asterisk.  The last line of comment text
 * should be immediately followed on the next line by the closing
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Original Author <author@example.com>
 */
class PMPro_Membership_Info {
	/**
	 * Description
	 *
	 * @return void
	 */
	public static function init() {
		// add_action( 'admin_menu', array( __CLASS__, 'pmpro_customizer_plugin_menu' ) );
		// add_action( 'pmpro_membership_level_after_other_settings', array( __CLASS__, 'pmpro_customizer_return_action' ), 5 );
		add_action( 'init', array( __CLASS__, 'pmpro_return_levels' ) );
	}
	/**
	 * Add a page to the dashboard menu.
	 */
	public static function pmpro_return_levels() {
		$pmpro_levels = pmpro_getAllLevels();
		return $pmpro_levels;
	}
	/**
	 * Add a page to the dashboard menu.
	 */
	public static function pmpro_customizer_return_action() {
		$return = '<table class="form-table"><tbody><tr><th>Some head thing</th><td><h4>' . __FUNCTION__ . '</h4>Some td thing<h4>parameter hw_api_object(xxxxx)<h4></td></tr></tbody></table>';
		// $return = __FUNCTION__;
		echo $return;
	}
	/**
	 * Add a page to the dashboard menu.
	 */
	public static function pmpro_customizer_plugin_menu() {
		add_dashboard_page( __( 'PMPro Dashboard', 'pmpro-customizer' ), __( 'PMPro Dash', 'pmpro-customizer' ), 'manage_options', 'pmpro-customizer-dash.php', array( __CLASS__, 'pmpro_customizer_dash_page' ) );

	}
	public static function pmpro_customizer_dash_page( $pmpro_levels ) {
			global $current_user;
		echo '<div class="wrap">';
		echo '<h2>' . __FUNCTION__ . '</h2>';
		echo '<h4>' . self::pmpro_customizer_redirect_1() . '</h4>';
		global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;
		$pmpro_levels = pmpro_getAllLevels();

		foreach ( $pmpro_levels as $level_object ) {
			foreach ( $level_object as $key => $level ) {
				// if ( $level_id == $level->id ) {
					// echo '<li>' . $key . ' = ' . $level . '</li>';
				// }
			}
		}
		echo '<pre>$pmpro_levels ';
		print_r( $pmpro_levels );
		echo '</pre>';

		$please_redirect = get_theme_mod( 'pmpro_enable_redirects' );
		echo '$please_redirect = ' . ( $please_redirect ? 'please_redirect' : 'nope' );

		if ( is_user_logged_in() && function_exists( 'pmpro_hasMembershipLevel' ) && pmpro_hasMembershipLevel() ) {
			$current_user->membership_level = pmpro_getMembershipLevelForUser( $current_user->ID );
			echo '<h4>Current user\'s Membership Level: <span style="color:maroon;">' . $current_user->membership_level->name . '</span></h4>';
		}
		$mods = get_theme_mods();
		if ( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) ) {
			echo get_permalink( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) );
		}

		return $newlevels;
		echo '<pre>$pmpro_levels';
		print_r( $pmpro_levels->id );
		echo '</pre>';

		echo '<pre>$mods';
		print_r( $mods );
		echo '</pre>';
		echo '</div>';
	}

	public static function pmpro_customizer_theme_mods() {
		global $current_user;
		$mods = get_theme_mods();

		$please_redirect = get_theme_mod( 'pmpro_enable_redirects' );
		echo '<h4>$please_redirect = ' . ( $please_redirect ? 'please_redirect' : 'nope' ) . '</h4>';

		if ( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) ) {
			echo get_permalink( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) );
		}
		echo '<h4>' . self::pmpro_customizer_redirect_1() . '</h4>';
		if ( is_user_logged_in() && function_exists( 'pmpro_hasMembershipLevel' ) && pmpro_hasMembershipLevel() ) {
			$current_user->membership_level = pmpro_getMembershipLevelForUser( $current_user->ID );
			echo '<h4>Current user\'s Membership Level: <span style="color:maroon;">' . $current_user->membership_level->name . '</span></h4>';
		}
		echo '<pre>';
		print_r( $mods );
		echo '</pre>';
	}
}
