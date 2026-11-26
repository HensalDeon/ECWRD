<?php
/**
 * One-click content setup.
 *
 * Creates the Home / About / Leadership pages, sets the static front page,
 * fills every field (English + Arabic) with the default copy, creates the
 * leader profiles with portraits and builds both navigation menus.
 *
 * Safe to run more than once: existing content is kept unless "overwrite" is
 * chosen. Available from Tools → ECWRD Setup, or `wp ecwrd import`.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

const ECWRD_IMPORT_PAGE = 'ecwrd-setup';

/**
 * Run the import.
 *
 * @param bool $overwrite Replace content that already exists.
 * @return string[] Log lines.
 */
function ecwrd_import_content( $overwrite = false ) {
	$log = array();

	if ( ! ecwrd_fields_plugin_ready() ) {
		return array( __( 'Secure Custom Fields (or ACF PRO) must be active first.', 'ecwrd' ) );
	}

	// 1. Pretty permalinks are needed for the /ar/ URLs.
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
		$log[] = __( 'Permalinks set to “Post name”.', 'ecwrd' );
	}

	// 2. Pages.
	$pages = array(
		'home'       => ecwrd_import_ensure_page( 'home', 'Home', '' ),
		'about'      => ecwrd_import_ensure_page( 'about', 'About Us', 'page-templates/about.php' ),
		'leadership' => ecwrd_import_ensure_page( 'leadership', 'Leadership', 'page-templates/leadership.php' ),
	);
	$log[] = __( 'Pages ready: Home, About Us, Leadership.', 'ecwrd' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $pages['home'] );

	// 3. Page fields.
	$defaults = ecwrd_default_content();
	foreach ( $pages as $area => $page_id ) {
		foreach ( ECWRD_LANGS as $lang ) {
			if ( $overwrite || ! metadata_exists( 'post', $page_id, 'content_' . $lang ) ) {
				update_field( "field_ecwrd_{$area}_content_{$lang}", $defaults[ $lang ][ $area ], $page_id );
			}
		}
	}
	$log[] = __( 'Page content filled (English + Arabic).', 'ecwrd' );

	// 4. Site settings.
	foreach ( ECWRD_LANGS as $lang ) {
		if ( $overwrite || null === get_option( 'options_content_' . $lang, null ) ) {
			update_field( 'field_ecwrd_settings_content_' . $lang, $defaults[ $lang ]['settings'], 'option' );
		}
	}
	foreach ( ecwrd_default_global_settings() as $key => $value ) {
		if ( $overwrite || null === get_option( 'options_' . $key, null ) ) {
			update_field( 'field_ecwrd_settings_' . $key, $value, 'option' );
		}
	}
	$log[] = __( 'Site settings filled.', 'ecwrd' );

	// 5. Leaders.
	$created = 0;
	foreach ( ecwrd_default_leaders() as $index => $leader ) {
		if ( ecwrd_import_leader( $leader, $index, $overwrite ) ) {
			++$created;
		}
	}
	/* translators: %d: number of leader profiles. */
	$log[] = sprintf( __( 'Leader profiles created: %d.', 'ecwrd' ), $created );

	// 6. Menus.
	$log = array_merge( $log, ecwrd_import_menus( $pages ) );

	flush_rewrite_rules( false );
	update_option( 'ecwrd_imported', time(), false );
	$log[] = __( 'Done.', 'ecwrd' );

	return $log;
}

/**
 * Find or create a page by slug and assign its template.
 *
 * @param string $slug     Slug.
 * @param string $title    Title.
 * @param string $template Template file ('' for default).
 * @return int Page ID.
 */
function ecwrd_import_ensure_page( $slug, $title, $template ) {
	$page_id = 0;

	if ( 'home' === $slug && 'page' === get_option( 'show_on_front' ) && get_post( (int) get_option( 'page_on_front' ) ) ) {
		$page_id = (int) get_option( 'page_on_front' );
	}

	if ( ! $page_id && $template ) {
		$ids     = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft', 'private' ),
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		$page_id = $ids ? (int) $ids[0] : 0;
	}

	if ( ! $page_id ) {
		$existing = get_page_by_path( $slug );
		$page_id  = $existing ? (int) $existing->ID : 0;
	}

	if ( ! $page_id ) {
		$page_id = (int) wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => $title,
				'post_name'   => $slug,
			)
		);
	} elseif ( 'publish' !== get_post_status( $page_id ) ) {
		wp_update_post(
			array(
				'ID'          => $page_id,
				'post_status' => 'publish',
			)
		);
	}

	if ( $template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}

	return $page_id;
}

/**
 * Create (or update) one leader profile.
 *
 * @param array $leader    Default leader data.
 * @param int   $index     Position.
 * @param bool  $overwrite Replace existing content.
 * @return bool Whether a new profile was created.
 */
function ecwrd_import_leader( array $leader, $index, $overwrite ) {
	$existing = get_posts(
		array(
			'post_type'      => ECWRD_LEADER_CPT,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_ecwrd_seed_key', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $leader['key'], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	if ( $existing && ! $overwrite ) {
		return false;
	}

	$post_id = $existing ? (int) $existing[0] : (int) wp_insert_post(
		array(
			'post_type'   => ECWRD_LEADER_CPT,
			'post_status' => 'publish',
			'post_title'  => $leader['en']['name'],
			'menu_order'  => $index + 1,
		)
	);

	if ( ! $post_id ) {
		return false;
	}

	update_post_meta( $post_id, '_ecwrd_seed_key', $leader['key'] );
	update_field( 'field_ecwrd_leader_section', $leader['section'], $post_id );
	update_field( 'field_ecwrd_leader_content_en', $leader['en'], $post_id );
	update_field( 'field_ecwrd_leader_content_ar', $leader['ar'], $post_id );

	if ( ! has_post_thumbnail( $post_id ) ) {
		$attachment_id = ecwrd_import_seed_image( $leader['image'], $post_id, $leader['en']['name'] );
		if ( $attachment_id ) {
			set_post_thumbnail( $post_id, $attachment_id );
		}
	}

	return ! $existing;
}

/**
 * Copy a bundled seed image into the Media Library.
 *
 * @param string $filename File in /assets/seed.
 * @param int    $post_id  Parent post.
 * @param string $alt      Alt text.
 * @return int Attachment ID or 0.
 */
function ecwrd_import_seed_image( $filename, $post_id, $alt ) {
	$source = ECWRD_DIR . '/assets/seed/' . $filename;
	if ( ! file_exists( $source ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( $filename );
	if ( ! $tmp || ! copy( $source, $tmp ) ) {
		return 0;
	}

	$attachment_id = media_handle_sideload(
		array(
			'name'     => $filename,
			'tmp_name' => $tmp,
		),
		$post_id,
		$alt
	);

	if ( is_wp_error( $attachment_id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	return (int) $attachment_id;
}

/**
 * Create the English and Arabic menus if their locations are empty.
 *
 * @param array<string, int> $pages Page IDs keyed by area.
 * @return string[] Log lines.
 */
function ecwrd_import_menus( array $pages ) {
	$log       = array();
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$defaults  = ecwrd_default_content();
	$menus     = array(
		'primary'    => array( 'Primary — English', 'en' ),
		'primary_ar' => array( 'Primary — Arabic', 'ar' ),
	);

	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}

		$existing = wp_get_nav_menu_object( $menu[0] );
		$menu_id  = $existing ? (int) $existing->term_id : (int) wp_create_nav_menu( $menu[0] );

		if ( ! $existing && $menu_id ) {
			$position = 0;
			foreach ( $pages as $area => $page_id ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => $defaults[ $menu[1] ]['nav'][ $area ],
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page_id,
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-position'  => ++$position,
					)
				);
			}
		}

		if ( $menu_id ) {
			$locations[ $location ] = $menu_id;
			/* translators: %s: menu name. */
			$log[] = sprintf( __( 'Menu assigned: %s.', 'ecwrd' ), $menu[0] );
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
	return $log;
}

/**
 * Tools → ECWRD Setup screen.
 */
function ecwrd_register_import_page() {
	add_management_page(
		__( 'ECWRD Setup', 'ecwrd' ),
		__( 'ECWRD Setup', 'ecwrd' ),
		'manage_options',
		ECWRD_IMPORT_PAGE,
		'ecwrd_render_import_page'
	);
}
add_action( 'admin_menu', 'ecwrd_register_import_page' );

/**
 * Render the setup screen.
 */
function ecwrd_render_import_page() {
	$log = get_transient( 'ecwrd_import_log_' . get_current_user_id() );
	delete_transient( 'ecwrd_import_log_' . get_current_user_id() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'ECWRD Setup', 'ecwrd' ); ?></h1>

		<?php if ( is_array( $log ) ) : ?>
			<div class="notice notice-success"><ul>
				<?php foreach ( $log as $line ) : ?>
					<li><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul></div>
		<?php endif; ?>

		<p><?php esc_html_e( 'Creates the Home, About Us and Leadership pages, fills all English and Arabic content, adds the leader profiles with portraits and builds the menus.', 'ecwrd' ); ?></p>

		<?php if ( ! ecwrd_fields_plugin_ready() ) : ?>
			<p><strong><?php esc_html_e( 'Install and activate Secure Custom Fields first.', 'ecwrd' ); ?></strong></p>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="ecwrd_import" />
				<?php wp_nonce_field( 'ecwrd_import' ); ?>
				<p>
					<label>
						<input type="checkbox" name="overwrite" value="1" />
						<?php esc_html_e( 'Overwrite existing content with the defaults (cannot be undone).', 'ecwrd' ); ?>
					</label>
				</p>
				<?php submit_button( __( 'Run setup', 'ecwrd' ) ); ?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Handle the setup form.
 */
function ecwrd_handle_import() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'ecwrd' ), 403 );
	}
	check_admin_referer( 'ecwrd_import' );

	$log = ecwrd_import_content( ! empty( $_POST['overwrite'] ) );
	set_transient( 'ecwrd_import_log_' . get_current_user_id(), $log, MINUTE_IN_SECONDS * 5 );

	wp_safe_redirect( admin_url( 'tools.php?page=' . ECWRD_IMPORT_PAGE ) );
	exit;
}
add_action( 'admin_post_ecwrd_import', 'ecwrd_handle_import' );

/**
 * Nudge administrators to run the setup once.
 */
function ecwrd_import_notice() {
	if ( get_option( 'ecwrd_imported' ) || ! current_user_can( 'manage_options' ) || ! ecwrd_fields_plugin_ready() ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'tools_page_' . ECWRD_IMPORT_PAGE === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p>%s</p><p><a class="button button-primary" href="%s">%s</a></p></div>',
		esc_html__( 'ECWRD theme: import the site content (pages, English + Arabic copy, leaders and menus) to get started.', 'ecwrd' ),
		esc_url( admin_url( 'tools.php?page=' . ECWRD_IMPORT_PAGE ) ),
		esc_html__( 'Open ECWRD Setup', 'ecwrd' )
	);
}
add_action( 'admin_notices', 'ecwrd_import_notice' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Import the ECWRD default content.
	 *
	 * ## OPTIONS
	 *
	 * [--overwrite]
	 * : Replace content that already exists.
	 *
	 * @param array $args       Positional args.
	 * @param array $assoc_args Flags.
	 */
	$ecwrd_cli_import = static function ( $args, $assoc_args ) {
		foreach ( ecwrd_import_content( ! empty( $assoc_args['overwrite'] ) ) as $line ) {
			WP_CLI::log( $line );
		}
		WP_CLI::success( 'ECWRD content imported.' );
	};
	WP_CLI::add_command( 'ecwrd import', $ecwrd_cli_import );
}
