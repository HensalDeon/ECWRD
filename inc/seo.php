<?php
/**
 * Language-aware titles and social meta.
 *
 * Steps aside automatically when a dedicated SEO plugin is active, to avoid
 * duplicate tags. Note: most SEO plugins are not aware of the theme's /ar/
 * URLs, so the built-in output is recommended for this site.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a dedicated SEO plugin handles meta output.
 *
 * @return bool
 */
function ecwrd_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

/**
 * Page-level SEO values for the current view.
 *
 * @return array{title:string, description:string}
 */
function ecwrd_seo_data() {
	$area = '';
	if ( is_front_page() ) {
		$area = 'home';
	} elseif ( is_page_template( 'page-templates/about.php' ) ) {
		$area = 'about';
	} elseif ( is_page_template( 'page-templates/leadership.php' ) ) {
		$area = 'leadership';
	}

	$site = ecwrd_setting( 'council_name' );
	$data = array(
		'title'       => '',
		'description' => (string) ecwrd_setting( 'seo.description' ),
	);

	if ( $area ) {
		$content = ecwrd_content( $area );
		if ( ! empty( $content['seo']['title'] ) ) {
			$data['title'] = $content['seo']['title'];
		}
		if ( ! empty( $content['seo']['description'] ) ) {
			$data['description'] = $content['seo']['description'];
		}
	} elseif ( is_404() ) {
		$data['title'] = ecwrd_setting( 'not_found.overline' ) . ' | ' . $site;
	} elseif ( is_singular() ) {
		$title = ecwrd_page_title();
		if ( $title ) {
			$data['title'] = $title . ' | ' . $site;
		}
	}

	return $data;
}

/**
 * Document <title>.
 *
 * @param string $title Title.
 * @return string
 */
function ecwrd_document_title( $title ) {
	if ( ecwrd_seo_plugin_active() ) {
		return $title;
	}
	$data = ecwrd_seo_data();
	return $data['title'] ? $data['title'] : $title;
}
add_filter( 'pre_get_document_title', 'ecwrd_document_title', 20 );

/**
 * Meta description, Open Graph and Twitter tags.
 */
function ecwrd_output_meta_tags() {
	if ( ecwrd_seo_plugin_active() ) {
		return;
	}

	$data     = ecwrd_seo_data();
	$title    = $data['title'] ? $data['title'] : wp_get_document_title();
	$url      = is_404() ? ecwrd_home_url() : ecwrd_current_url_in( ecwrd_lang() );
	$image_id = (int) ecwrd_global( 'og_image' );
	$image    = $image_id ? wp_get_attachment_image_src( $image_id, 'full' ) : false;
	$image    = $image ? $image : array( ECWRD_URI . '/assets/images/og-image.jpg', 1200, 630 );
	$locale   = ecwrd_is_rtl() ? 'ar_AE' : 'en_US';
	$alt      = ecwrd_is_rtl() ? 'en_US' : 'ar_AE';

	$tags = array(
		array( 'name', 'description', $data['description'] ),
		array( 'property', 'og:type', 'website' ),
		array( 'property', 'og:site_name', ecwrd_setting( 'council_name' ) ),
		array( 'property', 'og:title', $title ),
		array( 'property', 'og:description', $data['description'] ),
		array( 'property', 'og:url', $url ),
		array( 'property', 'og:image', $image[0] ),
		array( 'property', 'og:image:width', (string) $image[1] ),
		array( 'property', 'og:image:height', (string) $image[2] ),
		array( 'property', 'og:locale', $locale ),
		array( 'property', 'og:locale:alternate', $alt ),
		array( 'name', 'twitter:card', 'summary_large_image' ),
		array( 'name', 'twitter:title', $title ),
		array( 'name', 'twitter:description', $data['description'] ),
		array( 'name', 'twitter:image', $image[0] ),
	);

	foreach ( $tags as $tag ) {
		if ( '' === (string) $tag[2] ) {
			continue;
		}
		$value = in_array( $tag[1], array( 'og:url', 'og:image', 'twitter:image' ), true ) ? esc_url( $tag[2] ) : esc_attr( wp_strip_all_tags( $tag[2] ) );
		printf( '<meta %s="%s" content="%s" />' . "\n", esc_attr( $tag[0] ), esc_attr( $tag[1] ), $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}
}
add_action( 'wp_head', 'ecwrd_output_meta_tags', 2 );

/**
 * Title of a standard page in the current language.
 *
 * @param int|null $post_id Post ID.
 * @return string
 */
function ecwrd_page_title( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_queried_object_id();

	if ( ecwrd_is_rtl() && ecwrd_fields_available() ) {
		$ar = get_field( 'content_ar', $post_id );
		if ( is_array( $ar ) && ! empty( $ar['title'] ) ) {
			return $ar['title'];
		}
	}
	return get_the_title( $post_id );
}
