<?php
/**
 * Theme supports, menus, image sizes and front-end assets.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme supports and registrations.
 */
function ecwrd_setup() {
	load_theme_textdomain( 'ecwrd', ECWRD_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	register_nav_menus(
		array(
			'primary'    => __( 'Primary — English', 'ecwrd' ),
			'primary_ar' => __( 'Primary — Arabic', 'ecwrd' ),
		)
	);

	// 3:4 portraits, cropped from the top so faces are never cut off.
	add_image_size( 'ecwrd-portrait', 720, 960, array( 'center', 'top' ) );
}
add_action( 'after_setup_theme', 'ecwrd_setup' );

/**
 * Content width for embeds.
 */
function ecwrd_content_width() {
	$GLOBALS['content_width'] = 768; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}
add_action( 'after_setup_theme', 'ecwrd_content_width', 0 );

/**
 * Cache-busting version: file modification time in development, theme version otherwise.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string
 */
function ecwrd_asset_version( $relative_path ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$file = ECWRD_DIR . '/' . ltrim( $relative_path, '/' );
		if ( file_exists( $file ) ) {
			return (string) filemtime( $file );
		}
	}
	return ECWRD_VERSION;
}

/**
 * Whether the current view is rendered entirely by theme templates (no block content).
 *
 * @return bool
 */
function ecwrd_is_field_driven_view() {
	return is_front_page() || is_404() || is_page_template( array( 'page-templates/about.php', 'page-templates/leadership.php' ) );
}

/**
 * Enqueue front-end styles and scripts.
 */
function ecwrd_enqueue_assets() {
	$uri = ECWRD_URI . '/assets';

	wp_enqueue_style( 'ecwrd-fonts', $uri . '/css/fonts.css', array(), ecwrd_asset_version( 'assets/css/fonts.css' ) );
	wp_enqueue_style( 'lenis', $uri . '/vendor/lenis/lenis.css', array(), '1.3.26' );
	wp_enqueue_style( 'ecwrd-main', $uri . '/css/main.css', array( 'ecwrd-fonts', 'lenis' ), ecwrd_asset_version( 'assets/css/main.css' ) );

	wp_enqueue_script(
		'lenis',
		$uri . '/vendor/lenis/lenis.min.js',
		array(),
		'1.3.26',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_enqueue_script(
		'ecwrd-main',
		$uri . '/js/main.js',
		array( 'lenis' ),
		ecwrd_asset_version( 'assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	// Templates that render no block content don't need block styles.
	if ( ecwrd_is_field_driven_view() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'ecwrd_enqueue_assets', 20 );

/**
 * Early head output: preload the fonts used above the fold and flag JS support.
 *
 * The `js` class enables the reveal animations. A failsafe removes it if the
 * main script hasn't booted within 4s, so content can never stay hidden.
 */
function ecwrd_head_early() {
	$fonts = ecwrd_is_rtl()
		? array( 'alexandria-arabic.woff2' )
		: array( 'outfit-latin.woff2', 'manrope-latin.woff2' );

	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin />' . "\n",
			esc_url( ECWRD_URI . '/assets/fonts/' . $font )
		);
	}

	echo '<meta name="theme-color" content="#F7F3EC" />' . "\n";

	wp_print_inline_script_tag(
		"(function(d){var c=d.documentElement.classList;c.add('js');setTimeout(function(){if(!window.ecwrdBooted){c.remove('js');}},4000);})(document);"
	);
}
add_action( 'wp_head', 'ecwrd_head_early', 1 );

/**
 * Remove the emoji detection script and styles (not used by the design).
 */
function ecwrd_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'ecwrd_disable_emojis' );

/**
 * Allow SVG logos to be uploaded — only by users who may already post
 * unfiltered HTML (administrators), since SVG can contain script.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function ecwrd_allow_svg_for_admins( $mimes ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'ecwrd_allow_svg_for_admins' );
