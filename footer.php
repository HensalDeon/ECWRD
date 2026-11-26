<?php
/**
 * Site footer.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

$ecwrd_phone  = (string) ecwrd_global( 'phone' );
$ecwrd_email  = (string) ecwrd_global( 'email' );
$ecwrd_social = array_filter(
	array(
		'linkedin'  => array( 'LinkedIn', ecwrd_global( 'linkedin' ), 17 ),
		'instagram' => array( 'Instagram', ecwrd_global( 'instagram' ), 17 ),
		'x'         => array( 'X', ecwrd_global( 'x' ), 16 ),
	),
	static function ( $network ) {
		return ! empty( $network[1] );
	}
);
?>
	<footer class="site-footer">
		<div class="site-footer__inner">
			<div class="site-footer__grid">
				<div class="site-footer__brand">
					<?php ecwrd_logo( 'site-logo site-logo--footer' ); ?>
					<p class="site-footer__name"><?php echo esc_html( ecwrd_setting( 'council_name' ) ); ?></p>
				</div>

				<div class="site-footer__address">
					<h2 class="overline-label"><?php echo esc_html( ecwrd_setting( 'footer.address_title' ) ); ?></h2>
					<address class="site-footer__text">
						<?php foreach ( ecwrd_lines( ecwrd_setting( 'footer.address' ) ) as $ecwrd_line ) : ?>
							<span class="site-footer__line"><?php echo esc_html( $ecwrd_line ); ?></span>
						<?php endforeach; ?>
					</address>
				</div>

				<div class="site-footer__contact">
					<h2 class="overline-label"><?php echo esc_html( ecwrd_setting( 'footer.inquiries_title' ) ); ?></h2>
					<div class="site-footer__links">
						<?php if ( $ecwrd_phone ) : ?>
							<a class="site-footer__link" href="<?php echo esc_url( ecwrd_tel_uri( $ecwrd_phone ), array( 'tel' ) ); ?>" dir="ltr"><?php echo esc_html( $ecwrd_phone ); ?></a>
						<?php endif; ?>
						<?php if ( $ecwrd_email && is_email( $ecwrd_email ) ) : ?>
							<a class="site-footer__link" href="<?php echo esc_url( 'mailto:' . antispambot( $ecwrd_email ) ); ?>"><?php echo esc_html( antispambot( $ecwrd_email ) ); ?></a>
						<?php endif; ?>
					</div>
				</div>

				<div class="site-footer__follow">
					<?php if ( $ecwrd_social ) : ?>
						<h2 class="overline-label"><?php echo esc_html( ecwrd_setting( 'footer.follow_title' ) ); ?></h2>
						<div class="site-footer__social">
							<?php foreach ( $ecwrd_social as $ecwrd_network => $ecwrd_data ) : ?>
								<a class="site-footer__social-link" href="<?php echo esc_url( $ecwrd_data[1] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $ecwrd_data[0] ); ?>">
									<?php echo ecwrd_icon( $ecwrd_network, $ecwrd_data[2] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="site-footer__language">
						<h2 class="overline-label"><?php echo esc_html( ecwrd_setting( 'footer.language_label' ) ); ?></h2>
						<?php ecwrd_language_switcher( 'footer' ); ?>
					</div>
				</div>
			</div>

			<div class="site-footer__bottom">
				<p class="site-footer__copyright"><?php echo esc_html( str_replace( '{year}', wp_date( 'Y' ), ecwrd_setting( 'footer.copyright' ) ) ); ?></p>
				<p class="site-footer__location"><?php echo esc_html( ecwrd_setting( 'footer.location' ) ); ?></p>
			</div>
		</div>
	</footer>
</div><!-- .site-page -->

<?php wp_footer(); ?>
</body>
</html>
