<?php
/**
 * Template Name: Leadership
 *
 * Content: edit this page (English / Arabic tabs). People are managed under
 * "Leaders" in the admin menu.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ecwrd_c          = ecwrd_content( 'leadership' );
$ecwrd_board      = ecwrd_leaders( 'board' );
$ecwrd_executives = ecwrd_leaders( 'executive' );
$ecwrd_is_rtl     = ecwrd_is_rtl();
?>
<main id="main" class="site-main">

	<section class="hero hero--leadership">
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
			<div<?php echo ecwrd_fade_attrs( 0.85, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<p class="hero__text hero__text--tagline"><?php echo ecwrd_text( $ecwrd_c['hero']['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>
		</div>
	</section>

	<?php if ( $ecwrd_board ) : ?>
		<section class="board">
			<?php foreach ( $ecwrd_board as $ecwrd_i => $ecwrd_person ) : ?>
				<article class="leader<?php echo 1 === $ecwrd_i % 2 ? ' leader--alt' : ''; ?>">
					<div class="leader__grid">
						<div class="leader__media"<?php echo ecwrd_fade_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<div class="leader__portrait">
								<?php ecwrd_portrait( $ecwrd_person, '(min-width: 432px) 384px, calc(100vw - 48px)' ); ?>
							</div>
						</div>
						<div class="leader__content">
							<div<?php echo ecwrd_fade_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<span class="leader__num"><?php echo esc_html( ecwrd_index_label( $ecwrd_i ) ); ?></span>
								<h2 class="leader__name"><?php echo esc_html( $ecwrd_person['name'] ); ?></h2>
								<p class="leader__role overline-label"><?php echo esc_html( $ecwrd_person['role'] ); ?></p>
							</div>
							<?php if ( '' !== trim( $ecwrd_person['quote'] ) ) : ?>
								<div<?php echo ecwrd_fade_attrs( 0.15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<blockquote class="leader__quote"><?php echo $ecwrd_is_rtl ? '«' : '“'; ?><?php echo ecwrd_text( $ecwrd_person['quote'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $ecwrd_is_rtl ? '»' : '”'; ?></blockquote>
								</div>
							<?php endif; ?>
							<?php if ( '' !== trim( $ecwrd_person['bio'] ) ) : ?>
								<div<?php echo ecwrd_fade_attrs( 0.25 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<p class="leader__bio"><?php echo ecwrd_text( $ecwrd_person['bio'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</section>
	<?php endif; ?>

	<?php if ( $ecwrd_executives ) : ?>
		<section class="executives">
			<div class="container">
				<div<?php echo ecwrd_fade_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<h2 class="overline-label"><?php echo esc_html( $ecwrd_c['executives']['label'] ); ?></h2>
				</div>
				<div class="executives__grid">
					<?php foreach ( $ecwrd_executives as $ecwrd_i => $ecwrd_person ) : ?>
						<div<?php echo ecwrd_fade_attrs( $ecwrd_i * 0.12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<div class="executive">
								<?php ecwrd_portrait( $ecwrd_person, '(min-width: 1280px) 352px, (min-width: 1024px) calc((100vw - 224px) / 3), (min-width: 768px) calc((100vw - 176px) / 3), calc(100vw - 48px)' ); ?>
								<h3 class="executive__name"><?php echo esc_html( $ecwrd_person['name'] ); ?></h3>
								<p class="executive__role overline-label"><?php echo esc_html( $ecwrd_person['role'] ); ?></p>
								<?php if ( '' !== trim( $ecwrd_person['bio'] ) ) : ?>
									<p class="executive__bio"><?php echo ecwrd_text( $ecwrd_person['bio'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( '' !== trim( $ecwrd_c['closing']['quote'] ) ) : ?>
		<section class="chapter chapter--border chapter--tint">
			<div class="chapter__grid">
				<div class="chapter__aside"<?php echo ecwrd_fade_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<span class="overline-label overline-label--muted"><?php echo esc_html( $ecwrd_c['closing']['label'] ); ?></span>
				</div>
				<div class="chapter__body"<?php echo ecwrd_fade_attrs( 0.1 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<blockquote class="statement statement--plain"><?php echo ecwrd_text( $ecwrd_c['closing']['quote'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>
<?php
get_footer();
