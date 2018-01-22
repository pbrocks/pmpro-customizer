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
class PMPro_Customizer_Redirects {
	/**
	 * Description
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'pmpro_customizer_plugin_menu' ) );
		add_action( 'pmpro_membership_level_after_other_settings', array( __CLASS__, 'pmpro_customizer_return_action' ), 5 );
		add_filter( 'pmpro_membership_level_other_settings', array( __CLASS__, 'pmpro_customizer_return_filter' ) );
		// add_action( 'template_redirect', array( __CLASS__, 'pmpromh_template_redirect_homepage' ) );
		// add_filter( 'login_redirect', array( __CLASS__, 'pmpro_login_redirect' ), 10, 3 );
		// add_filter( 'login_redirect', array( __CLASS__, 'pmpro_multisite_login_redirect' ), 10, 3 );
		add_filter( 'pmpro_login_redirect_url', array( __CLASS__, 'pmpro_customizer_redirect_1' ) );
		add_shortcode( 'customizer-theme-mods', array( __CLASS__, 'pmpro_customizer_theme_mods' ), 20 );
	}
	/**
	 * Add a page to the dashboard menu.
	 */
	public static function pmpro_customizer_return_filter() {
		$return = '<tr><th>Some head thing</th><td>Some td thing<h4>Effine hw_api_object(parameter)<h4></td></tr>';
		// $return = __FUNCTION__;
		echo $return;
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
		$pmpro_levels = PMPro_Membership_Info::pmpro_return_levels();

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

		$level_2_redirect = get_option( 'pmpro_redirect_for_level_2' );
		if ( $level_2_redirect ) {
			echo '$level_2_redirect' . get_permalink( $level_2_redirect ) . '<br>';
		} else {
			echo '$level_2_redirect NOT<br>';
		}

		$please_redirect = get_option( 'pmpro_enable_redirects' );
		echo '$please_redirect = ' . ( $please_redirect ? 'please_redirect' : 'nope' ) . '<br>';

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

		$please_redirect = get_option( 'pmpro_enable_redirects' );
		echo '<h4>$please_redirect = ' . ( $please_redirect ? 'please_redirect' : 'nope' ) . '</h4>';

		$level_2_redirect = get_option( 'pmpro_redirect_for_level_2' );
		if ( $level_2_redirect ) {
			echo '$level_2_redirect = ' . get_permalink( $level_2_redirect ) . '<br>';
		} else {
			echo '$level_2_redirect NOT<br>';
		}

		$please_redirect = get_option( 'pmpro_enable_redirects' );
		echo '$please_redirect = ' . ( $please_redirect ? 'please_redirect' : 'nope' ) . '<br>';
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

	public static function pmpro_customizer_redirect_1() {
		$redirect_url = '';
		// pmpro_login_redirect_url
		$please_redirect = get_theme_mod( 'pmpro_enable_redirects' );
		if ( true === $please_redirect && get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) ) {
			$redirect_url = get_permalink( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) );
		}
		return $redirect_url;
	}

	/**
	 * Function to redirect member on login to their membership level's homepage
	 */
	public static function pmpromh_login_redirect( $redirect_to, $request, $user ) {
		// check level
		if ( ! empty( $user ) && ! empty( $user->ID ) && function_exists( 'pmpro_getMembershipLevelForUser' ) ) {
			$level = pmpro_getMembershipLevelForUser( $user->ID );
			$member_homepage_id = self::pmpromh_getHomepageForLevel( $level->id );

			if ( ! empty( $member_homepage_id ) ) {
				$redirect_to = get_permalink( $member_homepage_id );
			}
		}

		return $redirect_to;
	}

	/**
	 * Function to redirect member to their membership level's
	 * homepage when trying to access your site's front page
	 * (static page or posts page).
	 */
	public static function pmpromh_template_redirect_homepage() {
		global $current_user;
		// is there a user to check?
		if ( ! empty( $current_user->ID ) && is_front_page() ) {
			$member_homepage_id = self::pmpromh_getHomepageForLevel();
			if ( ! empty( $member_homepage_id ) && ! is_page( $member_homepage_id ) ) {
				wp_redirect( get_permalink( $member_homepage_id ) );
				exit;
			}
		}
	}

	/*
	Function to get a homepage for level
	*/
	public static function pmpromh_getHomepageForLevel( $level_id = null ) {
		if ( empty( $level_id ) && function_exists( 'pmpro_getMembershipLevelForUser' ) ) {
			global $current_user;
			$level = pmpro_getMembershipLevelForUser( $current_user->ID );
			if ( ! empty( $level ) ) {
				$level_id = $level->id;
			}
		}

		// look up by level
		if ( ! empty( $level_id ) ) {
			$member_homepage_id = get_option( 'pmpro_member_homepage_' . $level_id );
		} else {
			$member_homepage_id = false;
		}

		return $member_homepage_id;
	}

	/**
	 * Check if user was previously logged in.
	 * http://wordpress.org/support/topic/97314
	 *
	 * @return   [<description>]
	 */
	public static function redirect_current_user_can( $capability, $current_user ) {
		global $wpdb;

		$roles = get_option( $wpdb->prefix . 'user_roles' );
		$user_roles = $current_user->{$wpdb->prefix . 'capabilities'};
		$user_roles = array_keys( $user_roles, true );
		$role = $user_roles[0];
		$capabilities = $roles[ $role ]['capabilities'];

		if ( in_array( $capability, array_keys( $capabilities, true ) ) ) {
			// check array keys of capabilities for match against requested capability
			return true;
		}
		return false;
	}
	/**
	 * Redirect user after successful login.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $request URL the user is coming from.
	 * @param object $user Logged user's data.
	 * @return string
	 */
	public static function pmpro_login_redirect( $capability, $current_user ) {
		$redirect_to = self::pmpro_customizer_redirect_1();
		// Check for registered user.
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			// Do something different with admins.
			if ( in_array( 'administrator', $user->roles ) ) {
				// redirect them to the default place
				return '/customizer-dev-page/';
			} else {
				return $redirect_to;
			}
		} else {
			return $redirect_to;
		}
	}

	/**
	 * Redirect Multisite user after successful login.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $request URL the user is coming from.
	 * @param object $user Logged user's data.
	 * @return string
	 */
	public static function pmpro_multisite_login_redirect( $redirect_to, $request, $user ) {
		if ( is_multisite() ) {
			if ( is_super_admin() ) {
				return network_admin_url();
			} else {
				$user_info = get_userdata( $user );
				$redirect_url = $user_info->redirect_url;
				return $redirect_url;
			}
		}
	}
}
