<?php
/**
 * "Leaders" post type — the people shown on the Leadership page.
 *
 * Not publicly queryable: leaders have no single pages, they only feed the
 * Leadership template. Ordering uses the native "Order" attribute.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

const ECWRD_LEADER_CPT = 'ecwrd_leader';

/**
 * Register the post type.
 */
function ecwrd_register_post_types() {
	register_post_type(
		ECWRD_LEADER_CPT,
		array(
			'labels'              => array(
				'name'                  => __( 'Leaders', 'ecwrd' ),
				'singular_name'         => __( 'Leader', 'ecwrd' ),
				'add_new'               => __( 'Add Leader', 'ecwrd' ),
				'add_new_item'          => __( 'Add New Leader', 'ecwrd' ),
				'edit_item'             => __( 'Edit Leader', 'ecwrd' ),
				'new_item'              => __( 'New Leader', 'ecwrd' ),
				'search_items'          => __( 'Search Leaders', 'ecwrd' ),
				'not_found'             => __( 'No leaders found.', 'ecwrd' ),
				'all_items'             => __( 'All Leaders', 'ecwrd' ),
				'featured_image'        => __( 'Portrait', 'ecwrd' ),
				'set_featured_image'    => __( 'Set portrait', 'ecwrd' ),
				'remove_featured_image' => __( 'Remove portrait', 'ecwrd' ),
				'use_featured_image'    => __( 'Use as portrait', 'ecwrd' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'hierarchical'        => false,
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'ecwrd_register_post_types' );

/**
 * Title placeholder.
 *
 * @param string  $title Placeholder.
 * @param WP_Post $post  Post.
 * @return string
 */
function ecwrd_leader_title_placeholder( $title, $post ) {
	return ECWRD_LEADER_CPT === $post->post_type ? __( 'Internal name (filled automatically from the English name)', 'ecwrd' ) : $title;
}
add_filter( 'enter_title_here', 'ecwrd_leader_title_placeholder', 10, 2 );

/**
 * Keep the post title in sync with the English name so the admin list is readable.
 *
 * @param int|string $post_id Post ID.
 */
function ecwrd_sync_leader_title( $post_id ) {
	if ( ! is_numeric( $post_id ) || ECWRD_LEADER_CPT !== get_post_type( $post_id ) || wp_is_post_revision( (int) $post_id ) ) {
		return;
	}

	$en   = get_field( 'content_en', (int) $post_id );
	$name = is_array( $en ) && ! empty( $en['name'] ) ? sanitize_text_field( $en['name'] ) : '';

	if ( $name && get_the_title( (int) $post_id ) !== $name ) {
		remove_action( 'acf/save_post', 'ecwrd_sync_leader_title', 20 );
		wp_update_post(
			array(
				'ID'         => (int) $post_id,
				'post_title' => $name,
				'post_name'  => sanitize_title( $name ),
			)
		);
		add_action( 'acf/save_post', 'ecwrd_sync_leader_title', 20 );
	}
}
add_action( 'acf/save_post', 'ecwrd_sync_leader_title', 20 );

/**
 * Admin list columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function ecwrd_leader_columns( $columns ) {
	return array(
		'cb'             => $columns['cb'],
		'ecwrd_portrait' => __( 'Portrait', 'ecwrd' ),
		'title'          => __( 'Name', 'ecwrd' ),
		'ecwrd_role'     => __( 'Role', 'ecwrd' ),
		'ecwrd_section'  => __( 'Section', 'ecwrd' ),
		'ecwrd_order'    => __( 'Order', 'ecwrd' ),
	);
}
add_filter( 'manage_' . ECWRD_LEADER_CPT . '_posts_columns', 'ecwrd_leader_columns' );

/**
 * Render admin list columns.
 *
 * @param string $column  Column.
 * @param int    $post_id Post ID.
 */
function ecwrd_leader_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'ecwrd_portrait':
			echo get_the_post_thumbnail( $post_id, array( 48, 64 ), array( 'style' => 'width:48px;height:64px;object-fit:cover;object-position:top;' ) );
			break;
		case 'ecwrd_role':
			$en = function_exists( 'get_field' ) ? get_field( 'content_en', $post_id ) : array();
			echo esc_html( is_array( $en ) && isset( $en['role'] ) ? $en['role'] : '' );
			break;
		case 'ecwrd_section':
			$section = function_exists( 'get_field' ) ? get_field( 'leader_section', $post_id ) : '';
			echo esc_html( 'executive' === $section ? __( 'Executives', 'ecwrd' ) : __( 'Board', 'ecwrd' ) );
			break;
		case 'ecwrd_order':
			echo (int) get_post_field( 'menu_order', $post_id );
			break;
	}
}
add_action( 'manage_' . ECWRD_LEADER_CPT . '_posts_custom_column', 'ecwrd_leader_column_content', 10, 2 );

/**
 * Order the admin list the same way as the front end.
 *
 * @param WP_Query $query Query.
 */
function ecwrd_leader_admin_order( $query ) {
	if ( is_admin() && $query->is_main_query() && ECWRD_LEADER_CPT === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'ID'         => 'ASC',
			)
		);
	}
}
add_action( 'pre_get_posts', 'ecwrd_leader_admin_order' );
