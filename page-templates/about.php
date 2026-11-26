<?php
/**
 * Template Name: About
 *
 * Content: edit this page (English / Arabic tabs).
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ecwrd_c = ecwrd_content( 'about' );
?>
<main id="main" class="site-main">

	<section class="hero hero--about">
		<div class="hero__inner">
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
			<div<?php echo ecwrd_fade_attrs( 0.9, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php if ( $ecwrd_c['hero']['since'] ) : ?>
					<p class="hero__since"><?php echo esc_html( $ecwrd_c['hero']['since'] ); ?></p>
				<?php endif; ?>
				<p class="hero__text hero__text--intro"><?php echo ecwrd_text( $ecwrd_c['hero']['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>
		</div>
	</section>

	<?php ecwrd_chapter_open( $ecwrd_c['purpose']['num'], $ecwrd_c['purpose']['label'], array( 'border' => false ) ); ?>
		<?php ecwrd_reveal_lines( array( $ecwrd_c['purpose']['heading'] ), array( 'class' => 'heading-lg' ) ); ?>
		<div<?php echo ecwrd_fade_attrs( 0.2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<p class="lead"><?php echo ecwrd_text( $ecwrd_c['purpose']['body'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
	<?php ecwrd_chapter_close(); ?>

	<?php $ecwrd_mv = $ecwrd_c['mission_vision']; ?>
	<?php ecwrd_chapter_open( $ecwrd_mv['num'], $ecwrd_mv['label'] ); ?>
		<div class="statements">
			<div<?php echo ecwrd_fade_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<h3 class="overline-label"><?php echo esc_html( $ecwrd_mv['mission_title'] ); ?></h3>
				<blockquote class="statement"><?php echo ecwrd_text( $ecwrd_mv['mission'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
			</div>
			<div<?php echo ecwrd_fade_attrs( 0.15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<h3 class="overline-label"><?php echo esc_html( $ecwrd_mv['vision_title'] ); ?></h3>
				<blockquote class="statement"><?php echo ecwrd_text( $ecwrd_mv['vision'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
			</div>
		</div>
	<?php ecwrd_chapter_close(); ?>

	<?php ecwrd_chapter_open( $ecwrd_c['values']['num'], $ecwrd_c['values']['label'] ); ?>
		<div class="rows">
			<?php foreach ( $ecwrd_c['values']['items'] as $ecwrd_i => $ecwrd_item ) : ?>
				<div<?php echo ecwrd_fade_attrs( $ecwrd_i * 0.08 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="row row--value">
						<span class="row__num"><?php echo esc_html( ecwrd_index_label( $ecwrd_i ) ); ?></span>
						<h3 class="row__title row__title--value"><?php echo esc_html( $ecwrd_item['title'] ); ?></h3>
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
	<?php ecwrd_chapter_close(); ?>

</main>
<?php
get_footer();
