<?php
/**
 * Editable content fields.
 *
 * Fields are registered in code (not via the admin UI) so the content model
 * is version-controlled and cannot be broken accidentally from wp-admin.
 *
 * Requires Secure Custom Fields (free, wordpress.org) or ACF PRO — both
 * provide the Repeater field and Options Pages used here.
 *
 * Each content area is described ONCE by a schema; the builder turns it into
 * an English tab and an Arabic tab (`content_en` / `content_ar` groups) and
 * pre-fills both from inc/defaults.php.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

const ECWRD_SETTINGS_SLUG = 'ecwrd-settings';

/*
 * Schema helpers — keep the schema definitions below short and readable.
 */

/**
 * Single-line text field.
 *
 * @param string $label        Field label.
 * @param string $instructions Help text.
 * @return array
 */
function ecwrd_f_text( $label, $instructions = '' ) {
	return array(
		'type'         => 'text',
		'label'        => $label,
		'instructions' => $instructions,
	);
}

/**
 * Multi-line plain text field.
 *
 * @param string $label        Field label.
 * @param string $instructions Help text.
 * @param int    $rows         Visible rows.
 * @return array
 */
function ecwrd_f_textarea( $label, $instructions = '', $rows = 4 ) {
	return array(
		'type'         => 'textarea',
		'label'        => $label,
		'instructions' => $instructions,
		'rows'         => $rows,
	);
}

/**
 * Heading whose lines reveal one by one.
 *
 * @param string $label Field label.
 * @return array
 */
function ecwrd_f_lines( $label ) {
	return ecwrd_f_textarea( $label, __( 'One line per row — each row animates in separately.', 'ecwrd' ), 3 );
}

/**
 * Group of fields (a page section).
 *
 * @param string $label  Group label.
 * @param array  $fields Child schema nodes.
 * @return array
 */
function ecwrd_f_group( $label, array $fields ) {
	return array(
		'type'   => 'group',
		'label'  => $label,
		'fields' => $fields,
	);
}

/**
 * Repeatable rows (e.g. principles, values).
 *
 * @param string $label        Field label.
 * @param string $button       "Add row" button label.
 * @param array  $fields       Child schema nodes.
 * @param string $instructions Help text.
 * @return array
 */
function ecwrd_f_repeater( $label, $button, array $fields, $instructions = '' ) {
	return array(
		'type'         => 'repeater',
		'label'        => $label,
		'button'       => $button,
		'fields'       => $fields,
		'instructions' => $instructions,
	);
}

/**
 * Section header fields shared by every numbered section.
 *
 * @return array
 */
function ecwrd_f_section_header() {
	return array(
		'num'   => ecwrd_f_text( __( 'Section number', 'ecwrd' ), __( 'e.g. 01', 'ecwrd' ) ),
		'label' => ecwrd_f_text( __( 'Section label', 'ecwrd' ) ),
	);
}

/**
 * SEO fields (ignored when an SEO plugin such as Yoast is active).
 *
 * @return array
 */
function ecwrd_f_seo() {
	return ecwrd_f_group(
		__( 'Search engine (SEO)', 'ecwrd' ),
		array(
			'title'       => ecwrd_f_text( __( 'Browser / search title', 'ecwrd' ) ),
			'description' => ecwrd_f_textarea( __( 'Meta description', 'ecwrd' ), __( 'Around 150–160 characters.', 'ecwrd' ), 2 ),
		)
	);
}

/**
 * Content schemas, keyed by area. Area keys match inc/defaults.php.
 *
 * @return array<string, array>
 */
function ecwrd_schemas() {
	return array(
		'home'       => array(
			'hero'       => ecwrd_f_group(
				__( 'Hero', 'ecwrd' ),
				array(
					'overline' => ecwrd_f_text( __( 'Overline', 'ecwrd' ) ),
					'lines'    => ecwrd_f_lines( __( 'Heading', 'ecwrd' ) ),
					'tagline'  => ecwrd_f_textarea( __( 'Tagline', 'ecwrd' ), '', 3 ),
				)
			),
			'council'    => ecwrd_f_group(
				__( 'Section 01 — The Council', 'ecwrd' ),
				ecwrd_f_section_header() + array(
					'heading' => ecwrd_f_text( __( 'Heading', 'ecwrd' ) ),
					'body'    => ecwrd_f_textarea( __( 'Body', 'ecwrd' ) ),
				)
			),
			'principles' => ecwrd_f_group(
				__( 'Section 02 — Principles', 'ecwrd' ),
				ecwrd_f_section_header() + array(
					'items' => ecwrd_f_repeater(
						__( 'Principles', 'ecwrd' ),
						__( 'Add principle', 'ecwrd' ),
						array(
							'title' => ecwrd_f_text( __( 'Title', 'ecwrd' ) ),
							'desc'  => ecwrd_f_textarea( __( 'Description', 'ecwrd' ), '', 2 ),
						),
						__( 'Drag rows to reorder. Numbering is automatic.', 'ecwrd' )
					),
				)
			),
			'approach'   => ecwrd_f_group(
				__( 'Section 03 — Approach', 'ecwrd' ),
				ecwrd_f_section_header() + array(
					'heading'         => ecwrd_f_text( __( 'Heading', 'ecwrd' ) ),
					'body'            => ecwrd_f_textarea( __( 'Body', 'ecwrd' ), '', 3 ),
					'link_about'      => ecwrd_f_text( __( 'About page link label', 'ecwrd' ) ),
					'link_leadership' => ecwrd_f_text( __( 'Leadership page link label', 'ecwrd' ) ),
				)
			),
			'seo'        => ecwrd_f_seo(),
		),
		'about'      => array(
			'hero'           => ecwrd_f_group(
				__( 'Hero', 'ecwrd' ),
				array(
					'overline' => ecwrd_f_text( __( 'Overline', 'ecwrd' ) ),
					'lines'    => ecwrd_f_lines( __( 'Heading', 'ecwrd' ) ),
					'since'    => ecwrd_f_text( __( 'Since line', 'ecwrd' ) ),
					'intro'    => ecwrd_f_textarea( __( 'Introduction', 'ecwrd' ) ),
				)
			),
			'purpose'        => ecwrd_f_group(
				__( 'Section 01 — Purpose', 'ecwrd' ),
				ecwrd_f_section_header() + array(
					'heading' => ecwrd_f_text( __( 'Heading', 'ecwrd' ) ),
					'body'    => ecwrd_f_textarea( __( 'Body', 'ecwrd' ) ),
				)
			),
			'mission_vision' => ecwrd_f_group(
				__( 'Section 02 — Mission & Vision', 'ecwrd' ),
				ecwrd_f_section_header() + array(
					'mission_title' => ecwrd_f_text( __( 'Mission title', 'ecwrd' ) ),
					'mission'       => ecwrd_f_textarea( __( 'Mission statement', 'ecwrd' ), '', 3 ),
					'vision_title'  => ecwrd_f_text( __( 'Vision title', 'ecwrd' ) ),
					'vision'        => ecwrd_f_textarea( __( 'Vision statement', 'ecwrd' ), '', 3 ),
				)
			),
			'values'         => ecwrd_f_group(
				__( 'Section 03 — Values', 'ecwrd' ),
				ecwrd_f_section_header() + array(
					'items' => ecwrd_f_repeater(
						__( 'Values', 'ecwrd' ),
						__( 'Add value', 'ecwrd' ),
						array(
							'title' => ecwrd_f_text( __( 'Title', 'ecwrd' ) ),
							'desc'  => ecwrd_f_textarea( __( 'Description', 'ecwrd' ), '', 3 ),
						),
						__( 'Drag rows to reorder. Numbering is automatic.', 'ecwrd' )
					),
				)
			),
			'approach'       => ecwrd_f_group(
				__( 'Section 04 — Approach', 'ecwrd' ),
				ecwrd_f_section_header() + array(
					'heading' => ecwrd_f_text( __( 'Heading', 'ecwrd' ) ),
					'body'    => ecwrd_f_textarea( __( 'Body', 'ecwrd' ), '', 3 ),
				)
			),
			'seo'            => ecwrd_f_seo(),
		),
		'leadership' => array(
			'hero'       => ecwrd_f_group(
				__( 'Hero', 'ecwrd' ),
				array(
					'overline' => ecwrd_f_text( __( 'Overline', 'ecwrd' ) ),
					'lines'    => ecwrd_f_lines( __( 'Heading', 'ecwrd' ) ),
					'intro'    => ecwrd_f_textarea( __( 'Introduction', 'ecwrd' ), '', 3 ),
				)
			),
			'executives' => ecwrd_f_group(
				__( 'Executives section', 'ecwrd' ),
				array(
					'label' => ecwrd_f_text( __( 'Section label', 'ecwrd' ), __( 'People are managed under “Leaders” in the admin menu.', 'ecwrd' ) ),
				)
			),
			'closing'    => ecwrd_f_group(
				__( 'Closing statement', 'ecwrd' ),
				array(
					'label' => ecwrd_f_text( __( 'Label', 'ecwrd' ) ),
					'quote' => ecwrd_f_textarea( __( 'Statement', 'ecwrd' ), '', 3 ),
				)
			),
			'seo'        => ecwrd_f_seo(),
		),
		'settings'   => array(
			'council_name' => ecwrd_f_text( __( 'Organisation name', 'ecwrd' ), __( 'Used for the logo alt text and in the footer.', 'ecwrd' ) ),
			'footer'       => ecwrd_f_group(
				__( 'Footer', 'ecwrd' ),
				array(
					'address_title'   => ecwrd_f_text( __( 'Address title', 'ecwrd' ) ),
					'address'         => ecwrd_f_textarea( __( 'Address', 'ecwrd' ), __( 'One line per row.', 'ecwrd' ), 3 ),
					'inquiries_title' => ecwrd_f_text( __( 'Inquiries title', 'ecwrd' ) ),
					'follow_title'    => ecwrd_f_text( __( 'Social title', 'ecwrd' ) ),
					'language_label'  => ecwrd_f_text( __( 'Language switcher title', 'ecwrd' ) ),
					'copyright'       => ecwrd_f_text( __( 'Copyright line', 'ecwrd' ), __( '{year} is replaced with the current year.', 'ecwrd' ) ),
					'location'        => ecwrd_f_text( __( 'Location line', 'ecwrd' ) ),
				)
			),
			'not_found'    => ecwrd_f_group(
				__( '404 page', 'ecwrd' ),
				array(
					'overline'   => ecwrd_f_text( __( 'Overline', 'ecwrd' ) ),
					'heading'    => ecwrd_f_lines( __( 'Heading', 'ecwrd' ) ),
					'text'       => ecwrd_f_textarea( __( 'Text', 'ecwrd' ), '', 2 ),
					'link_label' => ecwrd_f_text( __( 'Home link label', 'ecwrd' ) ),
				)
			),
			'seo'          => ecwrd_f_group(
				__( 'Search engine (SEO) fallback', 'ecwrd' ),
				array(
					'description' => ecwrd_f_textarea( __( 'Default meta description', 'ecwrd' ), __( 'Used on pages without their own description.', 'ecwrd' ), 2 ),
				)
			),
		),
		'leader'     => array(
			'name'  => ecwrd_f_text( __( 'Full name', 'ecwrd' ) ),
			'role'  => ecwrd_f_text( __( 'Role / title', 'ecwrd' ) ),
			'quote' => ecwrd_f_textarea( __( 'Quote', 'ecwrd' ), __( 'Optional. Board members only. Do not add quotation marks — they are added automatically.', 'ecwrd' ), 3 ),
			'bio'   => ecwrd_f_textarea( __( 'Biography', 'ecwrd' ), '', 5 ),
		),
		'page'       => array(
			'title'   => ecwrd_f_text( __( 'Arabic title', 'ecwrd' ) ),
			'content' => array(
				'type'  => 'wysiwyg',
				'label' => __( 'Arabic content', 'ecwrd' ),
			),
		),
	);
}

/**
 * Convert a schema into ACF field arrays.
 *
 * @param array  $schema   Schema nodes keyed by field name.
 * @param mixed  $defaults Default values mirroring the schema.
 * @param string $prefix   Unique key prefix.
 * @return array
 */
function ecwrd_build_fields( array $schema, $defaults, $prefix ) {
	$fields = array();

	foreach ( $schema as $name => $node ) {
		$key     = $prefix . '_' . $name;
		$default = is_array( $defaults ) && array_key_exists( $name, $defaults ) ? $defaults[ $name ] : '';
		$field   = array(
			'key'          => $key,
			'name'         => $name,
			'label'        => $node['label'],
			'type'         => $node['type'],
			'instructions' => isset( $node['instructions'] ) ? $node['instructions'] : '',
		);

		switch ( $node['type'] ) {
			case 'group':
				$field['layout']     = 'block';
				$field['sub_fields'] = ecwrd_build_fields( $node['fields'], $default, $key );
				break;

			case 'repeater':
				$field['layout']       = 'block';
				$field['button_label'] = $node['button'];
				$field['sub_fields']   = ecwrd_build_fields( $node['fields'], array(), $key );
				$field['collapsed']    = $field['sub_fields'][0]['key'];
				break;

			case 'textarea':
				$field['rows']          = isset( $node['rows'] ) ? $node['rows'] : 4;
				$field['new_lines']     = '';
				$field['default_value'] = is_string( $default ) ? $default : '';
				break;

			case 'wysiwyg':
				$field['tabs']         = 'all';
				$field['toolbar']      = 'full';
				$field['media_upload'] = 1;
				break;

			default:
				$field['default_value'] = is_string( $default ) ? $default : '';
		}

		$fields[] = $field;
	}

	return $fields;
}

/**
 * English + Arabic tabs for an area.
 *
 * @param string $area   Schema / defaults key.
 * @param string $prefix Unique key prefix.
 * @return array
 */
function ecwrd_language_tabs( $area, $prefix ) {
	$schemas  = ecwrd_schemas();
	$defaults = ecwrd_default_content();
	$labels   = array(
		'en' => __( 'English', 'ecwrd' ),
		'ar' => __( 'Arabic — العربية', 'ecwrd' ),
	);
	$fields   = array();

	foreach ( ECWRD_LANGS as $lang ) {
		$area_defaults = isset( $defaults[ $lang ][ $area ] ) ? $defaults[ $lang ][ $area ] : array();

		$fields[] = array(
			'key'       => "{$prefix}_tab_{$lang}",
			'label'     => $labels[ $lang ],
			'type'      => 'tab',
			'placement' => 'top',
		);
		$fields[] = array(
			'key'        => "{$prefix}_content_{$lang}",
			'name'       => "content_{$lang}",
			'label'      => '',
			'type'       => 'group',
			'layout'     => 'block',
			'wrapper'    => array( 'class' => 'ecwrd-lang-group ecwrd-lang-' . $lang ),
			'sub_fields' => ecwrd_build_fields( $schemas[ $area ], $area_defaults, "{$prefix}_{$lang}" ),
		);
	}

	return $fields;
}

/**
 * Register the Site Settings options page.
 */
function ecwrd_register_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Site Settings', 'ecwrd' ),
			'menu_title' => __( 'Site Settings', 'ecwrd' ),
			'menu_slug'  => ECWRD_SETTINGS_SLUG,
			'capability' => 'edit_theme_options',
			'icon_url'   => 'dashicons-admin-site-alt3',
			'position'   => 59,
			'redirect'   => false,
		)
	);
}
add_action( 'acf/init', 'ecwrd_register_options_page' );

/**
 * Register all field groups.
 */
function ecwrd_register_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$global = ecwrd_default_global_settings();

	// Site settings.
	acf_add_local_field_group(
		array(
			'key'      => 'group_ecwrd_settings',
			'title'    => __( 'Site Settings', 'ecwrd' ),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => ECWRD_SETTINGS_SLUG,
					),
				),
			),
			'style'    => 'seamless',
			'fields'   => array_merge(
				array(
					array(
						'key'       => 'field_ecwrd_settings_tab_general',
						'label'     => __( 'General', 'ecwrd' ),
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'           => 'field_ecwrd_settings_logo',
						'name'          => 'logo',
						'label'         => __( 'Logo', 'ecwrd' ),
						'type'          => 'image',
						'return_format' => 'id',
						'preview_size'  => 'medium',
						'mime_types'    => 'svg,png,webp,jpg,jpeg',
						'instructions'  => __( 'Landscape logo used in the header and footer. Leave empty to use the built-in logo.', 'ecwrd' ),
					),
					array(
						'key'           => 'field_ecwrd_settings_og_image',
						'name'          => 'og_image',
						'label'         => __( 'Social sharing image', 'ecwrd' ),
						'type'          => 'image',
						'return_format' => 'id',
						'preview_size'  => 'medium',
						'instructions'  => __( '1200 × 630 px. Shown when the site is shared on social media.', 'ecwrd' ),
					),
					array(
						'key'           => 'field_ecwrd_settings_phone',
						'name'          => 'phone',
						'label'         => __( 'Phone', 'ecwrd' ),
						'type'          => 'text',
						'default_value' => $global['phone'],
						'wrapper'       => array( 'width' => '50' ),
					),
					array(
						'key'           => 'field_ecwrd_settings_email',
						'name'          => 'email',
						'label'         => __( 'Email', 'ecwrd' ),
						'type'          => 'email',
						'default_value' => $global['email'],
						'wrapper'       => array( 'width' => '50' ),
					),
					array(
						'key'       => 'field_ecwrd_settings_tab_social',
						'label'     => __( 'Social', 'ecwrd' ),
						'type'      => 'tab',
						'placement' => 'top',
					),
					array(
						'key'           => 'field_ecwrd_settings_linkedin',
						'name'          => 'linkedin',
						'label'         => 'LinkedIn',
						'type'          => 'url',
						'default_value' => $global['linkedin'],
						'instructions'  => __( 'Leave any social link empty to hide its icon.', 'ecwrd' ),
					),
					array(
						'key'           => 'field_ecwrd_settings_instagram',
						'name'          => 'instagram',
						'label'         => 'Instagram',
						'type'          => 'url',
						'default_value' => $global['instagram'],
					),
					array(
						'key'           => 'field_ecwrd_settings_x',
						'name'          => 'x',
						'label'         => 'X (Twitter)',
						'type'          => 'url',
						'default_value' => $global['x'],
					),
				),
				ecwrd_language_tabs( 'settings', 'field_ecwrd_settings' )
			),
		)
	);

	// Home page.
	acf_add_local_field_group(
		array(
			'key'            => 'group_ecwrd_home',
			'title'          => __( 'Home Page Content', 'ecwrd' ),
			'location'       => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'position'       => 'acf_after_title',
			'hide_on_screen' => array( 'the_content', 'excerpt', 'discussion', 'comments', 'author', 'featured_image' ),
			'fields'         => ecwrd_language_tabs( 'home', 'field_ecwrd_home' ),
		)
	);

	// About page.
	acf_add_local_field_group(
		array(
			'key'            => 'group_ecwrd_about',
			'title'          => __( 'About Page Content', 'ecwrd' ),
			'location'       => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-templates/about.php',
					),
				),
			),
			'position'       => 'acf_after_title',
			'hide_on_screen' => array( 'the_content', 'excerpt', 'discussion', 'comments', 'author', 'featured_image' ),
			'fields'         => ecwrd_language_tabs( 'about', 'field_ecwrd_about' ),
		)
	);

	// Leadership page.
	acf_add_local_field_group(
		array(
			'key'            => 'group_ecwrd_leadership',
			'title'          => __( 'Leadership Page Content', 'ecwrd' ),
			'location'       => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-templates/leadership.php',
					),
				),
			),
			'position'       => 'acf_after_title',
			'hide_on_screen' => array( 'the_content', 'excerpt', 'discussion', 'comments', 'author', 'featured_image' ),
			'fields'         => ecwrd_language_tabs( 'leadership', 'field_ecwrd_leadership' ),
		)
	);

	// Leader profile.
	acf_add_local_field_group(
		array(
			'key'      => 'group_ecwrd_leader',
			'title'    => __( 'Profile', 'ecwrd' ),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => ECWRD_LEADER_CPT,
					),
				),
			),
			'position' => 'acf_after_title',
			'fields'   => array_merge(
				array(
					array(
						'key'           => 'field_ecwrd_leader_section',
						'name'          => 'leader_section',
						'label'         => __( 'Section', 'ecwrd' ),
						'type'          => 'button_group',
						'choices'       => array(
							'board'     => __( 'Board (large profile with quote)', 'ecwrd' ),
							'executive' => __( 'Executives (card grid)', 'ecwrd' ),
						),
						'default_value' => 'board',
						'instructions'  => __( 'Set the portrait with “Featured image” (3:4 portrait, at least 720 × 960 px). Use “Order” under Page Attributes to arrange people.', 'ecwrd' ),
					),
				),
				ecwrd_language_tabs( 'leader', 'field_ecwrd_leader' )
			),
		)
	);

	// Arabic version of any other standard page (Privacy Policy, etc.).
	acf_add_local_field_group(
		array(
			'key'      => 'group_ecwrd_page_ar',
			'title'    => __( 'Arabic Version', 'ecwrd' ),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'page',
					),
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'default',
					),
					array(
						'param'    => 'page_type',
						'operator' => '!=',
						'value'    => 'front_page',
					),
				),
			),
			'position' => 'normal',
			'fields'   => array(
				array(
					'key'        => 'field_ecwrd_page_content_ar',
					'name'       => 'content_ar',
					'label'      => '',
					'type'       => 'group',
					'layout'     => 'block',
					'wrapper'    => array( 'class' => 'ecwrd-lang-group ecwrd-lang-ar' ),
					'sub_fields' => ecwrd_build_fields( ecwrd_schemas()['page'], array(), 'field_ecwrd_page_ar' ),
				),
			),
		)
	);
}
add_action( 'acf/init', 'ecwrd_register_field_groups' );
