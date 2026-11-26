<?php
/**
 * Site header: fixed bar with primary nav, centred logo, language switch and
 * the mobile menu overlay.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

$ecwrd_nav = ecwrd_nav_items();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php echo esc_html( ecwrd_ui( 'skip' ) ); ?></a>

<header class="site-header" data-header>
	<div class="site-header__bar">
		<nav class="site-nav" aria-label="<?php echo esc_attr( ecwrd_ui( 'primary' ) ); ?>">
			<?php foreach ( $ecwrd_nav as $ecwrd_item ) : ?>
				<a
					class="site-nav__link<?php echo $ecwrd_item['current'] ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( $ecwrd_item['url'] ); ?>"
					<?php echo $ecwrd_item['current'] ? 'aria-current="page"' : ''; ?>
				><?php echo esc_html( $ecwrd_item['label'] ); ?><span class="site-nav__underline" aria-hidden="true"></span></a>
			<?php endforeach; ?>
		</nav>

		<a class="site-header__logo" href="<?php echo esc_url( ecwrd_home_url() ); ?>" aria-label="<?php echo esc_attr( ecwrd_setting( 'council_name' ) ); ?>">
			<?php ecwrd_logo( 'site-logo site-logo--header' ); ?>
		</a>

		<div class="site-header__actions">
			<div class="site-header__lang">
				<?php ecwrd_language_switcher( 'header' ); ?>
			</div>
			<button
				type="button"
				class="menu-toggle"
				aria-expanded="false"
				aria-controls="mobile-menu"
				aria-label="<?php echo esc_attr( ecwrd_ui( 'open_menu' ) ); ?>"
				data-menu-toggle
				data-label-open="<?php echo esc_attr( ecwrd_ui( 'open_menu' ) ); ?>"
				data-label-close="<?php echo esc_attr( ecwrd_ui( 'close_menu' ) ); ?>"
			>
				<span class="menu-toggle__icon menu-toggle__icon--open"><?php echo ecwrd_icon( 'menu', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="menu-toggle__icon menu-toggle__icon--close"><?php echo ecwrd_icon( 'close', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</button>
		</div>
	</div>

	<div class="mobile-menu" id="mobile-menu" data-mobile-menu inert>
		<nav class="mobile-menu__nav" aria-label="<?php echo esc_attr( ecwrd_ui( 'mobile' ) ); ?>">
			<?php foreach ( $ecwrd_nav as $ecwrd_index => $ecwrd_item ) : ?>
				<div class="mobile-menu__item" style="--item:<?php echo (int) $ecwrd_index; ?>">
					<a
						class="mobile-menu__link<?php echo $ecwrd_item['current'] ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( $ecwrd_item['url'] ); ?>"
						<?php echo $ecwrd_item['current'] ? 'aria-current="page"' : ''; ?>
					><?php echo esc_html( $ecwrd_item['label'] ); ?></a>
				</div>
			<?php endforeach; ?>
		</nav>
		<div class="mobile-menu__footer">
			<?php ecwrd_language_switcher( 'mobile' ); ?>
			<span class="mobile-menu__location"><?php echo esc_html( ecwrd_setting( 'footer.location' ) ); ?></span>
		</div>
	</div>
</header>

<div class="page" data-page>
