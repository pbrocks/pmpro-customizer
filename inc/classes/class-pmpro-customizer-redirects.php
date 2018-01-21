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
		// add_action( 'template_redirect', array( __CLASS__, 'pmpromh_template_redirect_homepage' ) );
		add_filter( 'login_redirect', array( __CLASS__, 'pmpro_login_redirect' ), 10, 3 );
		// add_filter( 'login_redirect', array( __CLASS__, 'pmpro_multisite_login_redirect' ), 10, 3 );
		// add_filter( 'login_redirect', 'pmpromh_login_redirect', 10, 3 );
		add_shortcode( 'customizer-theme-mods', array( __CLASS__, 'pmpro_customizer_theme_mods' ), 20 );
	}
	/**
	 * Add a page to the dashboard menu.
	 */
	public static function pmpro_customizer_plugin_menu() {
		add_dashboard_page( __( 'PMPro Dashboard', 'pmpro-customizer' ), __( 'PMPro Dash', 'pmpro-customizer' ), 'manage_options', 'pmpro-customizer-dash.php', array( __CLASS__, 'pmpro_customizer_dash_page' ) );

	}
	public static function pmpro_customizer_dash_page() {
			global $current_user;
		echo '<div class="wrap">';
		echo '<h2>' . __FUNCTION__ . '</h2>';
		echo '<h4>' . self::pmpro_customizer_redirect_1() . '</h4>';
		if ( is_user_logged_in() && function_exists( 'pmpro_hasMembershipLevel' ) && pmpro_hasMembershipLevel() ) {
			$current_user->membership_level = pmpro_getMembershipLevelForUser( $current_user->ID );
			echo '<h4>Current user\'s Membership Level: <span style="color:maroon;">' . $current_user->membership_level->name . '</span></h4>';
		}
		$mods = get_theme_mods();
		if ( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) ) {
			echo get_permalink( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) );
		}
		echo '<pre>';
		print_r( $mods );
		echo '</pre>';
		echo '</div>';
	}

	public static function pmpro_customizer_theme_mods() {
		global $current_user;
		$mods = get_theme_mods();
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
		if ( get_theme_mod( 'pmpro_dropdown_page_redirect_1' ) ) {
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
	public static function pmpro_login_redirect( $redirect_to, $request, $user ) {
		$redirect_to = self::pmpro_customizer_redirect_1();
		// Check for registered user.
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			// Do something different with admins.
			if ( in_array( 'administrator', $user->roles ) ) {
				// redirect them to the default place
				return '/customizer-dev-page/';
			} else {
				return '/sample-page/';
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
