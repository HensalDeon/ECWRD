<?php
/**
 * Admin experience: dependency notice, clean editing screens, RTL inputs.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the installed fields plugin supports everything the theme needs
 * (Repeater + Options Pages: Secure Custom Fields or ACF PRO).
 *
 * @return bool
 */
function ecwrd_fields_plugin_ready() {
	return function_exists( 'acf_add_options_page' ) && function_exists( 'acf_get_field_type' ) && acf_get_field_type( 'repeater' );
}

/**
 * Notice when the fields plugin is missing or is the limited free ACF build.
 */
function ecwrd_dependency_notice() {
	if ( ! current_user_can( 'install_plugins' ) || ecwrd_fields_plugin_ready() ) {
		return;
	}

	$install = admin_url( 'plugin-install.php?s=secure+custom+fields&tab=search&type=term' );
	$message = function_exists( 'get_field' )
		? __( 'The ECWRD theme needs repeater fields and a settings page, which the free “Advanced Custom Fields” plugin does not include. Please replace it with the free <strong>Secure Custom Fields</strong> plugin (your saved content is compatible) or use ACF PRO.', 'ecwrd' )
		: __( 'The ECWRD theme uses the free <strong>Secure Custom Fields</strong> plugin to make all content editable. Until it is installed, the site shows its default content.', 'ecwrd' );

	printf(
		'<div class="notice notice-warning"><p>%s</p><p><a class="button button-primary" href="%s">%s</a></p></div>',
		wp_kses( $message, array( 'strong' => array() ) ),
		esc_url( $install ),
		esc_html__( 'Install Secure Custom Fields', 'ecwrd' )
	);
}
add_action( 'admin_notices', 'ecwrd_dependency_notice' );

/**
 * Field-driven pages (home, about, leadership) are edited through their
 * fields only — hide the block editor so editors aren't confused by an
 * editor whose content is never displayed.
 */
function ecwrd_remove_editor_for_field_pages() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
		return;
	}

	$template = get_page_template_slug( $post_id );
	if ( (int) get_option( 'page_on_front' ) === $post_id || in_array( $template, array( 'page-templates/about.php', 'page-templates/leadership.php' ), true ) ) {
		remove_post_type_support( 'page', 'editor' );
	}
}
add_action( 'load-post.php', 'ecwrd_remove_editor_for_field_pages' );

/**
 * Admin styles (RTL inputs for Arabic fields, tidy groups).
 *
 * @param string $hook Current admin page.
 */
function ecwrd_admin_assets( $hook ) {
	$screen           = get_current_screen();
	$is_fields_screen = in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || ( $screen && false !== strpos( (string) $screen->id, ECWRD_SETTINGS_SLUG ) );

	if ( $is_fields_screen ) {
		wp_enqueue_style( 'ecwrd-admin', ECWRD_URI . '/assets/css/admin.css', array(), ecwrd_asset_version( 'assets/css/admin.css' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'ecwrd_admin_assets' );

/**
 * Show which page is which in the Pages list.
 *
 * @param array   $states Post states.
 * @param WP_Post $post   Post.
 * @return array
 */
function ecwrd_display_post_states( $states, $post ) {
	if ( 'page' !== $post->post_type ) {
		return $states;
	}
	$template = get_page_template_slug( $post );
	if ( 'page-templates/about.php' === $template ) {
		$states['ecwrd'] = __( 'About Template', 'ecwrd' );
	} elseif ( 'page-templates/leadership.php' === $template ) {
		$states['ecwrd'] = __( 'Leadership Template', 'ecwrd' );
	}
	return $states;
}
add_filter( 'display_post_states', 'ecwrd_display_post_states', 10, 2 );
