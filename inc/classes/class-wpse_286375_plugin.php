<?php
/**
 * Plugin: WPSE 286375
 * Description: A dynamic dropdown-pages control.
 * Plugin URI: https://wordpress.stackexchange.com/q/286375
 * https://gist.github.com/westonruter/00c351830e0a15cef7672b748720a7ff
 * Version: 0.1.0
 * Author: Weston Ruter, XWP
 * Author URI: https://weston.ruter.net/
 * License: GPLv2+
 *
 * @package WPSE_286375
 */

/**
 * Class WPSE_286375_Plugin
 */
class WPSE_286375_Plugin {

	/**
	 * Init.
	 */
	public function init() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );

		add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_control_templates' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_controls_scripts' ) );
	}

	/**
	 * Customize register.
	 *
	 * @param WP_Customize_Manager $wp_customize Manager.
	 */
	public function customize_register( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_setting(
			'favorite_page', array(
				'default' => 0,
				'sanitize_callback' => function( $value ) {
					$value = intval( $value );
					if ( $value <= 0 ) {
						return new WP_Error( 'invalid_page_id_int', __( 'Page ID must be integer.', 'wpse-286375' ) );
					}
					if ( 'page' !== get_post_type( $value ) ) {
						return new WP_Error( 'invalid_page_id_post_type', __( 'ID must be for a page.', 'wpse-286375' ) );
					}
					return $value;
				},
			)
		);
	}

	/**
	 * Enqueue controls scripts.
	 */
	public function enqueue_controls_scripts() {
		$handle = 'wpse-286375-controls';
		wp_enqueue_script( $handle, plugin_dir_url( __FILE__ ) . 'wpse-286375-controls.js', array( 'customize-controls' ) );
		$exports = array(
			'label' => __( 'Featured Page', 'wpse-286375' ),
			'description' => __( 'Select your a page that you\'d like to feature on the site.', 'wpse-286375' ),
			'setting' => 'favorite_page',
		);
		wp_add_inline_script( $handle, sprintf( 'wpse286375controls.init( %s );', wp_json_encode( $exports ) ) );
	}

	/**
	 * Print templates.
	 *
	 * @global WP_Customize_Manager $wp_customize
	 */
	public function print_control_templates() {
		global $wp_customize;
		?>

		<script type="text/html" id="tmpl-customize-control-dropdown-pages-content">
			<#
			var inputId = _.uniqueId( 'customize-control-dropdown-pages-input-' );
			var descriptionId = _.uniqueId( 'customize-control-dropdown-pages-description-' );
			var describedByAttr = data.description ? ' aria-describedby="' + descriptionId + '" ' : '';
			#>
			<# if ( data.label ) { #>
				<label for="{{ inputId }}" class="customize-control-title">
					{{ data.label }}
				</label>
			<# } #>
			<# if ( data.description ) { #>
				<span id="{{ descriptionId }}" class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<#
			var inputAttrs = {
				id: inputId,
				'data-customize-setting-key-link': 'default'
			};
			if ( 'textarea' === data.type ) {
				inputAttrs.rows = '5';
			} else if ( 'button' === data.type ) {
				inputAttrs['class'] = 'button button-secondary';
				inputAttrs.type = 'button';
			} else {
				inputAttrs.type = data.type;
			}
			if ( data.description ) {
				inputAttrs['aria-describedby'] = descriptionId;
			}
			_.extend( inputAttrs, data.input_attrs );
			#>
			<# delete inputAttrs.type; #>

			<?php
			$show_option_none = __( '&mdash; Select &mdash;', 'default' );
			$option_none_value = '0';
			$dropdown = wp_dropdown_pages(
				array(
					'name' => '{{ inputId }}',
					'echo' => 0,
					'show_option_none' => $show_option_none,
					'option_none_value' => $option_none_value,
				)
			);
			if ( empty( $dropdown ) ) {
				$dropdown = sprintf( '<select id="%1$s" name="%1$s">', '{{ inputId }}' );
				$dropdown .= sprintf( '<option value="%1$s">%2$s</option>', esc_attr( $option_none_value ), esc_html( $show_option_none ) );
				$dropdown .= '</select>';
			}

			// Hackily add in the data link parameter.
			$dropdown = str_replace(
				'<select',
				'<select <# _.each( _.extend( inputAttrs ), function( value, key ) { #> {{{ key }}}="{{ value }}" <# }); #> ',
				$dropdown
			);

			// Even more hacikly add auto-draft page stubs.
			// @todo Eventually this should be removed in favor of the pages being injected into the underlying get_pages() call. See <https://github.com/xwp/wp-customize-posts/pull/250>.
			$nav_menus_created_posts_setting = $wp_customize->get_setting( 'nav_menus_created_posts' );
			if ( $nav_menus_created_posts_setting && current_user_can( 'publish_pages' ) ) {
				$auto_draft_page_options = '';
				foreach ( $nav_menus_created_posts_setting->value() as $auto_draft_page_id ) {
					$post = get_post( $auto_draft_page_id );
					if ( $post && 'page' === $post->post_type ) {
						$auto_draft_page_options .= sprintf( '<option value="%1$s">%2$s</option>', esc_attr( $post->ID ), esc_html( $post->post_title ) );
					}
				}
				if ( $auto_draft_page_options ) {
					$dropdown = str_replace( '</select>', $auto_draft_page_options . '</select>', $dropdown );
				}
			}

			echo $dropdown;
			?>
			<?php if ( current_user_can( 'publish_pages' ) && current_user_can( 'edit_theme_options' ) ) : // Currently tied to menus functionality. ?>
				<# if ( data.allowAddition ) { #>
					<button type="button" class="button-link add-new-toggle">
						<?php
						/* translators: %s: add new page label */
						printf( __( '+ %s', 'default' ), get_post_type_object( 'page' )->labels->add_new_item );
						?>
					</button>
					<div class="new-content-item">
						<label for="create-input-{{ inputId }}"><span class="screen-reader-text"><?php esc_html_e( 'New page title', 'default' ); ?></span></label>
						<input type="text" id="create-input-{{ inputId }}" class="create-item-input" placeholder="<?php esc_attr_e( 'New page title&hellip;', 'default' ); ?>">
						<button type="button" class="button add-content"><?php esc_html_e( 'Add', 'default' ); ?></button>
					</div>
				<# } #>
			<?php endif; ?>
		</script>
		<?php
	}
}

// if ( version_compare( strtok( get_bloginfo( 'version' ), '-' ), '4.9', '>=' ) ) {
// $wpse_286375 = new WPSE_286375_Plugin();
// $wpse_286375->init();
// }
