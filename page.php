<?php
/**
 * Standard page (e.g. Privacy Policy).
 *
 * English: the page title and editor content.
 * Arabic: the "Arabic Version" box below the editor (falls back to English).
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$ecwrd_ar_content = '';
	if ( ecwrd_is_rtl() && ecwrd_fields_available() ) {
		$ecwrd_ar         = get_field( 'content_ar' );
		$ecwrd_ar_content = is_array( $ecwrd_ar ) && ! empty( $ecwrd_ar['content'] ) ? $ecwrd_ar['content'] : '';
	}
	?>
	<main id="main" class="site-main">
		<section class="hero hero--page">
			<div class="hero__inner">
				<div<?php echo ecwrd_fade_attrs( 0.15, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<p class="overline-label"><?php echo esc_html( ecwrd_setting( 'council_name' ) ); ?></p>
				</div>
				<?php
				ecwrd_reveal_lines(
					array( ecwrd_page_title() ),
					array(
						'tag'     => 'h1',
						'class'   => 'display hero__heading',
						'delay'   => 0.35,
						'on_load' => true,
					)
				);
				?>
			</div>
		</section>

		<section class="page-content">
			<div class="container">
				<div class="entry-content"<?php echo ecwrd_fade_attrs( 0.6, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php
					if ( $ecwrd_ar_content ) {
						echo wp_kses_post( $ecwrd_ar_content );
					} else {
						the_content();
					}
					?>
				</div>
			</div>
		</section>
	</main>
	<?php
endwhile;

get_footer();
