<?php
/**
 * Content accessors used by templates.
 *
 * Templates never call get_field() directly; they go through these helpers,
 * which merge what editors saved over the defaults in inc/defaults.php. That
 * keeps templates simple and guarantees the site renders fully even before
 * content has been imported or when the fields plugin is inactive.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the custom fields plugin (SCF / ACF) is available.
 *
 * @return bool
 */
function ecwrd_fields_available() {
	return function_exists( 'get_field' );
}

/**
 * Merge saved values over defaults.
 *
 * - Never-saved values (null) fall back to the default.
 * - Saved empty strings are respected (an editor may clear a field on purpose).
 * - Lists (repeaters) are taken as saved.
 *
 * @param mixed $saved    Saved value.
 * @param mixed $defaults Default value.
 * @return mixed
 */
function ecwrd_resolve( $saved, $defaults ) {
	if ( null === $saved ) {
		return $defaults;
	}

	if ( ! is_array( $defaults ) ) {
		return is_array( $saved ) ? $defaults : $saved;
	}

	// Lists come from repeaters: take the saved rows as they are.
	if ( array() === $defaults || array_keys( $defaults ) === range( 0, count( $defaults ) - 1 ) ) {
		return is_array( $saved ) ? array_values( $saved ) : array();
	}

	$saved = is_array( $saved ) ? $saved : array();
	$out   = array();
	foreach ( $defaults as $key => $default ) {
		$out[ $key ] = ecwrd_resolve( array_key_exists( $key, $saved ) ? $saved[ $key ] : null, $default );
	}
	return $out;
}

/**
 * Find the first page using a given page template.
 *
 * @param string $template Template file, e.g. 'page-templates/about.php'.
 * @return int Page ID or 0.
 */
function ecwrd_page_id_by_template( $template ) {
	static $cache = array();

	if ( ! isset( $cache[ $template ] ) ) {
		$ids                = get_posts(
			array(
				'post_type'              => 'page',
				'post_status'            => 'publish',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'meta_key'               => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'             => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'orderby'                => 'menu_order ID',
				'order'                  => 'ASC',
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
			)
		);
		$cache[ $template ] = $ids ? (int) $ids[0] : 0;
	}

	return $cache[ $template ];
}

/**
 * Localised permalink of the page using a template (falls back to home).
 *
 * @param string $template Template file.
 * @return string
 */
function ecwrd_template_url( $template ) {
	$id = ecwrd_page_id_by_template( $template );
	return $id ? get_permalink( $id ) : ecwrd_home_url();
}

/**
 * Content for a page area in the current language.
 *
 * @param string   $area    'home', 'about' or 'leadership'.
 * @param int|null $post_id Page ID; defaults to the queried page.
 * @return array
 */
function ecwrd_content( $area, $post_id = null ) {
	$lang     = ecwrd_lang();
	$all      = ecwrd_default_content();
	$defaults = $all[ $lang ][ $area ];
	$post_id  = $post_id ? (int) $post_id : (int) get_queried_object_id();

	if ( ! ecwrd_fields_available() || ! $post_id || ! metadata_exists( 'post', $post_id, 'content_' . $lang ) ) {
		return $defaults;
	}

	return ecwrd_resolve( get_field( 'content_' . $lang, $post_id ), $defaults );
}

/**
 * Translatable site setting (dot notation, e.g. 'footer.copyright').
 *
 * @param string $path Setting path.
 * @return mixed
 */
function ecwrd_setting( $path ) {
	static $settings = array();

	$lang = ecwrd_lang();

	if ( ! isset( $settings[ $lang ] ) ) {
		$all      = ecwrd_default_content();
		$defaults = $all[ $lang ]['settings'];
		$saved    = null;

		if ( ecwrd_fields_available() && null !== get_option( 'options_content_' . $lang, null ) ) {
			$saved = get_field( 'content_' . $lang, 'option' );
		}
		$settings[ $lang ] = ecwrd_resolve( $saved, $defaults );
	}

	$value = $settings[ $lang ];
	foreach ( explode( '.', $path ) as $segment ) {
		$value = is_array( $value ) && isset( $value[ $segment ] ) ? $value[ $segment ] : '';
	}
	return $value;
}

/**
 * Language-neutral site setting (phone, email, social links, logo...).
 *
 * @param string $key Setting name.
 * @return mixed
 */
function ecwrd_global( $key ) {
	$defaults = ecwrd_default_global_settings();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	if ( ! ecwrd_fields_available() || null === get_option( 'options_' . $key, null ) ) {
		return $default;
	}

	$value = get_field( $key, 'option' );
	return null === $value ? $default : $value;
}

/**
 * Logo URL (uploaded logo, or the bundled SVG).
 *
 * @return string
 */
function ecwrd_logo_url() {
	$id  = (int) ecwrd_global( 'logo' );
	$url = $id ? wp_get_attachment_image_url( $id, 'full' ) : '';
	return $url ? $url : ECWRD_URI . '/assets/images/logo.svg';
}

/**
 * Phone number as a tel: URI.
 *
 * @param string $phone Display number.
 * @return string
 */
function ecwrd_tel_uri( $phone ) {
	return 'tel:' . preg_replace( '/[^\d+]/', '', $phone );
}

/**
 * Split a multi-line field into trimmed, non-empty lines.
 *
 * @param string $text Text.
 * @return string[]
 */
function ecwrd_lines( $text ) {
	$lines = preg_split( '/\R/u', (string) $text );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * Escape plain text for output, keeping editor line breaks.
 *
 * @param string $text Text.
 * @return string Safe HTML.
 */
function ecwrd_text( $text ) {
	return nl2br( esc_html( trim( (string) $text ) ), false );
}

/**
 * People shown on the Leadership page.
 *
 * Uses the "Leaders" post type once any leader is published; until then the
 * bundled defaults are shown so the page is never empty.
 *
 * @param string $section 'board' or 'executive'.
 * @return array<int, array{name:string, role:string, quote:string, bio:string, image_id:int, image_url:string}>
 */
function ecwrd_leaders( $section ) {
	$lang  = ecwrd_lang();
	$posts = array();

	if ( ecwrd_fields_available() ) {
		$posts = get_posts(
			array(
				'post_type'      => ECWRD_LEADER_CPT,
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'ID'         => 'ASC',
				),
				'no_found_rows'  => true,
			)
		);
	}

	$people = array();

	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_section = get_field( 'leader_section', $post->ID );
			if ( ( $post_section ? $post_section : 'board' ) !== $section ) {
				continue;
			}
			$fields = get_field( 'content_' . $lang, $post->ID );
			$fields = is_array( $fields ) ? $fields : array();

			// Fall back to English for anything not yet translated.
			if ( 'en' !== $lang ) {
				$en = get_field( 'content_en', $post->ID );
				foreach ( (array) $en as $k => $v ) {
					if ( ( ! isset( $fields[ $k ] ) || '' === $fields[ $k ] ) && 'quote' !== $k ) {
						$fields[ $k ] = $v;
					}
				}
			}

			$people[] = array(
				'name'      => isset( $fields['name'] ) && '' !== $fields['name'] ? $fields['name'] : get_the_title( $post ),
				'role'      => isset( $fields['role'] ) ? (string) $fields['role'] : '',
				'quote'     => isset( $fields['quote'] ) ? (string) $fields['quote'] : '',
				'bio'       => isset( $fields['bio'] ) ? (string) $fields['bio'] : '',
				'image_id'  => (int) get_post_thumbnail_id( $post ),
				'image_url' => '',
			);
		}
		return $people;
	}

	foreach ( ecwrd_default_leaders() as $leader ) {
		if ( $leader['section'] !== $section ) {
			continue;
		}
		$people[] = $leader[ $lang ] + array(
			'image_id'  => 0,
			'image_url' => ECWRD_URI . '/assets/seed/' . $leader['image'],
		);
	}
	return $people;
}

/**
 * Primary navigation items for the current language.
 *
 * Editors manage these under Appearance → Menus ("Primary — English" and
 * "Primary — Arabic" locations). Without menus, Home/About/Leadership are used.
 *
 * @return array<int, array{label:string, url:string, current:bool}>
 */
function ecwrd_nav_items() {
	$lang      = ecwrd_lang();
	$locations = get_nav_menu_locations();
	$location  = 'ar' === $lang ? 'primary_ar' : 'primary';

	if ( empty( $locations[ $location ] ) && ! empty( $locations['primary'] ) ) {
		$location = 'primary';
	}

	$queried = get_queried_object_id();
	$items   = array();

	if ( ! empty( $locations[ $location ] ) ) {
		$menu_items = wp_get_nav_menu_items( $locations[ $location ] );
		foreach ( (array) $menu_items as $item ) {
			if ( (int) $item->menu_item_parent ) {
				continue; // The design has a single level.
			}
			$url     = ecwrd_localize_url( $item->url, $lang );
			$current = ! is_404() && (
				( 'post_type' === $item->type && (int) $item->object_id === $queried && is_singular() )
				|| ( untrailingslashit( $url ) === untrailingslashit( ecwrd_current_url_in( $lang ) ) )
			);

			$items[] = array(
				'label'   => $item->title,
				'url'     => $url,
				'current' => $current,
			);
		}
		return $items;
	}

	$all    = ecwrd_default_content();
	$labels = $all[ $lang ]['nav'];
	$pages  = array(
		'home'       => (int) get_option( 'page_on_front' ),
		'about'      => ecwrd_page_id_by_template( 'page-templates/about.php' ),
		'leadership' => ecwrd_page_id_by_template( 'page-templates/leadership.php' ),
	);

	foreach ( $pages as $key => $page_id ) {
		if ( 'home' !== $key && ! $page_id ) {
			continue;
		}
		$items[] = array(
			'label'   => $labels[ $key ],
			'url'     => 'home' === $key ? ecwrd_home_url() : get_permalink( $page_id ),
			'current' => 'home' === $key ? is_front_page() : ( is_page( $page_id ) ),
		);
	}
	return $items;
}
