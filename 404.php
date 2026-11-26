<?php
/**
 * Not found.
 *
 * Content: Site Settings → 404 page.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<section class="hero hero--about">
		<div class="hero__inner">
			<div<?php echo ecwrd_fade_attrs( 0.15, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<p class="overline-label"><?php echo esc_html( ecwrd_setting( 'not_found.overline' ) ); ?></p>
			</div>
			<?php
			ecwrd_reveal_lines(
				ecwrd_setting( 'not_found.heading' ),
				array(
					'tag'     => 'h1',
					'class'   => 'display hero__heading',
					'delay'   => 0.35,
					'on_load' => true,
				)
			);
			?>
			<div<?php echo ecwrd_fade_attrs( 0.85, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<p class="hero__text hero__text--tagline"><?php echo ecwrd_text( ecwrd_setting( 'not_found.text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<div class="link-row">
					<a class="arrow-link" href="<?php echo esc_url( ecwrd_home_url() ); ?>">
						<?php echo esc_html( ecwrd_setting( 'not_found.link_label' ) ); ?>
						<span class="arrow-link__icon"><?php echo ecwrd_icon( ecwrd_is_rtl() ? 'arrow-left' : 'arrow-right', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
