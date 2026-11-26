<?php
/**
 * Bilingual (English / Arabic) routing.
 *
 * English lives at the site root (/, /about/, /leadership/) and Arabic is
 * served from an /ar/ prefix (/ar/, /ar/about/, /ar/leadership/). Each
 * language therefore has its own crawlable, cache-safe URL — important on
 * hosts with full-page caching such as WP Engine, where a cookie or
 * localStorage based switch would serve the wrong language from cache.
 *
 * With plain permalinks the theme falls back to a `?ecwrd_lang=ar` argument.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

const ECWRD_LANGS        = array( 'en', 'ar' );
const ECWRD_DEFAULT_LANG = 'en';
const ECWRD_QUERY_VAR    = 'ecwrd_lang';

/**
 * Bump when the rewrite rules below change so they are flushed once.
 */
const ECWRD_REWRITE_VERSION = '1';

/**
 * The language of the current front-end request.
 *
 * @return string 'en' or 'ar'.
 */
function ecwrd_lang() {
	global $wp;

	// The language belongs to the request URL, not to the post query. WordPress
	// skips the /ar/ page rule when the page doesn't exist (404), so the path
	// prefix is checked as well.
	$lang = get_query_var( ECWRD_QUERY_VAR );

	if ( $wp instanceof WP ) {
		if ( isset( $wp->query_vars[ ECWRD_QUERY_VAR ] ) ) {
			$lang = $wp->query_vars[ ECWRD_QUERY_VAR ];
		} elseif ( ecwrd_uses_pretty_urls() && preg_match( '#^ar(/|$)#', (string) $wp->request ) ) {
			$lang = 'ar';
		}
	}

	return in_array( $lang, ECWRD_LANGS, true ) ? $lang : ECWRD_DEFAULT_LANG;
}

/**
 * Whether the current request is in Arabic.
 *
 * @return bool
 */
function ecwrd_is_rtl() {
	return 'ar' === ecwrd_lang();
}

/**
 * Whether URLs are built with the /ar/ prefix (pretty permalinks) or a query arg.
 *
 * @return bool
 */
function ecwrd_uses_pretty_urls() {
	return (bool) get_option( 'permalink_structure' );
}

/**
 * Register the query var and rewrite rules.
 */
function ecwrd_register_language_routes() {
	add_rewrite_tag( '%' . ECWRD_QUERY_VAR . '%', '(en|ar)' );
	add_rewrite_rule( '^ar/?$', 'index.php?' . ECWRD_QUERY_VAR . '=ar', 'top' );
	add_rewrite_rule( '^ar/(.+?)/?$', 'index.php?pagename=$matches[1]&' . ECWRD_QUERY_VAR . '=ar', 'top' );

	if ( get_option( 'ecwrd_rewrite_version' ) !== ECWRD_REWRITE_VERSION ) {
		flush_rewrite_rules( false );
		update_option( 'ecwrd_rewrite_version', ECWRD_REWRITE_VERSION, true );
	}
}
add_action( 'init', 'ecwrd_register_language_routes' );

/**
 * Flush rules whenever the theme is (re)activated.
 */
function ecwrd_flush_routes_on_activation() {
	ecwrd_register_language_routes();
	flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'ecwrd_flush_routes_on_activation' );

/**
 * `/ar/` on its own only carries the language var, which WordPress would treat
 * as the blog index. Point it at the static front page instead.
 *
 * @param array $query_vars Parsed query vars.
 * @return array
 */
function ecwrd_route_arabic_front_page( $query_vars ) {
	if ( isset( $query_vars[ ECWRD_QUERY_VAR ] ) && 1 === count( $query_vars ) && 'page' === get_option( 'show_on_front' ) ) {
		$front_page_id = (int) get_option( 'page_on_front' );
		if ( $front_page_id ) {
			$query_vars['page_id'] = $front_page_id;
		}
	}
	return $query_vars;
}
add_filter( 'request', 'ecwrd_route_arabic_front_page' );

/**
 * WordPress would otherwise "correct" /ar/about/ to the English permalink.
 *
 * @param string|false $redirect_url Canonical URL.
 * @return string|false
 */
function ecwrd_disable_canonical_redirect_for_arabic( $redirect_url ) {
	return ecwrd_is_rtl() ? false : $redirect_url;
}
add_filter( 'redirect_canonical', 'ecwrd_disable_canonical_redirect_for_arabic' );

/**
 * Path of the WordPress home URL (e.g. '' or '/subdir'), without trailing slash.
 *
 * @return string
 */
function ecwrd_home_path() {
	$path = wp_parse_url( home_url(), PHP_URL_PATH );
	return $path ? untrailingslashit( $path ) : '';
}

/**
 * Convert an internal URL to the given language.
 *
 * External URLs, admin URLs and anchors are returned untouched.
 *
 * @param string      $url  URL to convert.
 * @param string|null $lang Target language; defaults to the current one.
 * @return string
 */
function ecwrd_localize_url( $url, $lang = null ) {
	$lang = $lang ? $lang : ecwrd_lang();
	$home = untrailingslashit( home_url() );

	if ( ! is_string( $url ) || '' === $url || 0 !== strpos( $url, $home ) ) {
		return $url;
	}

	$url = ecwrd_delocalize_url( $url );

	if ( 'ar' !== $lang ) {
		return $url;
	}

	if ( ! ecwrd_uses_pretty_urls() ) {
		return add_query_arg( ECWRD_QUERY_VAR, 'ar', $url );
	}

	$relative = substr( $url, strlen( $home ) );
	$relative = '' === $relative ? '/' : $relative;

	// Never prefix WordPress internals.
	if ( preg_match( '#^/(wp-admin|wp-content|wp-includes|wp-json|wp-login\.php|feed)(/|$|\?)#', $relative ) ) {
		return $url;
	}

	return $home . '/ar' . ( '/' === $relative[0] ? $relative : '/' . $relative );
}

/**
 * Strip any language marker from an internal URL (returns the English URL).
 *
 * @param string $url URL to convert.
 * @return string
 */
function ecwrd_delocalize_url( $url ) {
	$home = untrailingslashit( home_url() );

	if ( 0 !== strpos( $url, $home ) ) {
		return $url;
	}

	$url      = remove_query_arg( ECWRD_QUERY_VAR, $url );
	$relative = substr( $url, strlen( $home ) );

	if ( preg_match( '#^/ar(/|$|\?|\#)#', $relative ) ) {
		$relative = substr( $relative, 3 );
		$relative = ( '' === $relative || '/' !== $relative[0] ) ? '/' . $relative : $relative;
	}

	return $home . $relative;
}

/**
 * Home URL in the current language.
 *
 * @param string|null $lang Optional language.
 * @return string
 */
function ecwrd_home_url( $lang = null ) {
	return ecwrd_localize_url( home_url( '/' ), $lang );
}

/**
 * URL of the current request, in the given language (used by the switcher and hreflang).
 *
 * @param string $lang Target language.
 * @return string
 */
function ecwrd_current_url_in( $lang ) {
	if ( is_singular() ) {
		$url = get_permalink( get_queried_object_id() );
		$url = $url ? ecwrd_delocalize_url( $url ) : home_url( '/' );
	} elseif ( is_404() ) {
		$url = home_url( '/' );
	} else {
		global $wp;
		$url = home_url( user_trailingslashit( (string) $wp->request ) );
		$url = ecwrd_delocalize_url( $url );
	}

	if ( is_front_page() ) {
		$url = home_url( '/' );
	}

	return ecwrd_localize_url( $url, $lang );
}

/**
 * Localise page permalinks while browsing in Arabic, so every link generated by
 * WordPress (menus, canonical, get_permalink) keeps the visitor in Arabic.
 *
 * @param string $url Permalink.
 * @return string
 */
function ecwrd_filter_page_link( $url ) {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ! did_action( 'wp' ) ) {
		return $url;
	}
	return ecwrd_is_rtl() ? ecwrd_localize_url( $url, 'ar' ) : $url;
}
add_filter( 'page_link', 'ecwrd_filter_page_link', 20 );

/**
 * Localise custom-link menu items that point inside the site.
 *
 * @param array $atts Link attributes.
 * @return array
 */
function ecwrd_filter_menu_link_atts( $atts ) {
	if ( ! empty( $atts['href'] ) && ecwrd_is_rtl() ) {
		$atts['href'] = ecwrd_localize_url( $atts['href'], 'ar' );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'ecwrd_filter_menu_link_atts' );

/**
 * Output lang/dir on <html> for the active language.
 *
 * @param string $output Default attributes.
 * @return string
 */
function ecwrd_language_attributes( $output ) {
	if ( is_admin() ) {
		return $output;
	}
	return ecwrd_is_rtl() ? 'lang="ar" dir="rtl"' : 'lang="en" dir="ltr"';
}
add_filter( 'language_attributes', 'ecwrd_language_attributes' );

/**
 * Make is_rtl() reflect the active language on the front end.
 */
function ecwrd_set_text_direction() {
	global $wp_locale;
	if ( ! is_admin() && $wp_locale instanceof WP_Locale ) {
		$wp_locale->text_direction = ecwrd_is_rtl() ? 'rtl' : 'ltr';
	}
}
add_action( 'wp', 'ecwrd_set_text_direction' );

/**
 * Body classes for the active language.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function ecwrd_language_body_class( $classes ) {
	$classes[] = 'lang-' . ecwrd_lang();
	return $classes;
}
add_filter( 'body_class', 'ecwrd_language_body_class' );

/**
 * Output hreflang alternates for search engines.
 */
function ecwrd_output_hreflang() {
	if ( is_404() || is_search() ) {
		return;
	}
	printf( '<link rel="alternate" hreflang="en" href="%s" />' . "\n", esc_url( ecwrd_current_url_in( 'en' ) ) );
	printf( '<link rel="alternate" hreflang="ar" href="%s" />' . "\n", esc_url( ecwrd_current_url_in( 'ar' ) ) );
	printf( '<link rel="alternate" hreflang="x-default" href="%s" />' . "\n", esc_url( ecwrd_current_url_in( 'en' ) ) );
}
add_action( 'wp_head', 'ecwrd_output_hreflang', 5 );
