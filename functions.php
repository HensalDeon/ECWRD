<?php
/**
 * ECWRD Institutional theme bootstrap.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

define( 'ECWRD_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );
define( 'ECWRD_DIR', get_template_directory() );
define( 'ECWRD_URI', get_template_directory_uri() );

$ecwrd_includes = array(
	'inc/defaults.php',      // Default bilingual content (seed + fallback).
	'inc/i18n.php',          // English / Arabic routing.
	'inc/post-types.php',    // "Leaders" post type.
	'inc/fields.php',        // Editable fields (Secure Custom Fields / ACF PRO).
	'inc/content.php',       // Content accessors used by templates.
	'inc/setup.php',         // Supports, menus, assets.
	'inc/template-tags.php', // Markup helpers.
	'inc/seo.php',           // Titles and social meta.
	'inc/admin.php',         // Admin experience.
	'inc/importer.php',      // One-click content setup.
);

foreach ( $ecwrd_includes as $ecwrd_file ) {
	require_once ECWRD_DIR . '/' . $ecwrd_file;
}
unset( $ecwrd_includes, $ecwrd_file );
