<?php
/**
 * Home page.
 *
 * Content: Pages → Home (English / Arabic tabs).
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ecwrd_c     = ecwrd_content( 'home', (int) get_option( 'page_on_front' ) );
$ecwrd_arrow = ecwrd_icon( ecwrd_is_rtl() ? 'arrow-left' : 'arrow-right', 15 );
?>
<main id="main" class="site-main">

	<section class="hero hero--home">
		<canvas class="hero__canvas" data-sand-canvas aria-hidden="true"></canvas>
		<div class="hero__inner hero__inner--home">
			<div<?php echo ecwrd_fade_attrs( 0.15, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<p class="overline-label"><?php echo esc_html( $ecwrd_c['hero']['overline'] ); ?></p>
			</div>
			<?php
			ecwrd_reveal_lines(
				$ecwrd_c['hero']['lines'],
				array(
					'tag'     => 'h1',
					'class'   => 'display hero__heading',
					'delay'   => 0.35,
					'on_load' => true,
				)
			);
			?>
			<div<?php echo ecwrd_fade_attrs( 0.95, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<p class="hero__text hero__text--tagline"><?php echo ecwrd_text( $ecwrd_c['hero']['tagline'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>
		</div>
	</section>

	<?php ecwrd_chapter_open( $ecwrd_c['council']['num'], $ecwrd_c['council']['label'] ); ?>
		<?php ecwrd_reveal_lines( array( $ecwrd_c['council']['heading'] ), array( 'class' => 'heading-lg' ) ); ?>
		<div<?php echo ecwrd_fade_attrs( 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<p class="lead"><?php echo ecwrd_text( $ecwrd_c['council']['body'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
	<?php ecwrd_chapter_close(); ?>

	<?php ecwrd_chapter_open( $ecwrd_c['principles']['num'], $ecwrd_c['principles']['label'] ); ?>
		<div class="rows">
			<?php foreach ( $ecwrd_c['principles']['items'] as $ecwrd_i => $ecwrd_item ) : ?>
				<div<?php echo ecwrd_fade_attrs( $ecwrd_i * 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="row row--principle">
						<span class="row__num"><?php echo esc_html( ecwrd_index_label( $ecwrd_i ) ); ?></span>
						<h3 class="row__title row__title--principle"><?php echo esc_html( $ecwrd_item['title'] ); ?></h3>
						<p class="row__desc"><?php echo ecwrd_text( $ecwrd_item['desc'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php ecwrd_chapter_close(); ?>

	<?php ecwrd_chapter_open( $ecwrd_c['approach']['num'], $ecwrd_c['approach']['label'] ); ?>
		<?php ecwrd_reveal_lines( array( $ecwrd_c['approach']['heading'] ), array( 'class' => 'heading-lg' ) ); ?>
		<div<?php echo ecwrd_fade_attrs( 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<p class="lead"><?php echo ecwrd_text( $ecwrd_c['approach']['body'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
		<div<?php echo ecwrd_fade_attrs( 0.4 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="link-row">
				<?php if ( $ecwrd_c['approach']['link_about'] ) : ?>
					<a class="arrow-link" href="<?php echo esc_url( ecwrd_template_url( 'page-templates/about.php' ) ); ?>">
						<?php echo esc_html( $ecwrd_c['approach']['link_about'] ); ?>
						<span class="arrow-link__icon"><?php echo $ecwrd_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $ecwrd_c['approach']['link_leadership'] ) : ?>
					<a class="arrow-link" href="<?php echo esc_url( ecwrd_template_url( 'page-templates/leadership.php' ) ); ?>">
						<?php echo esc_html( $ecwrd_c['approach']['link_leadership'] ); ?>
						<span class="arrow-link__icon"><?php echo $ecwrd_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php ecwrd_chapter_close(); ?>

</main>
<?php
get_footer();
