<?php
/**
 * Markup helpers shared by templates.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon (Lucide, ISC licence — same set as the original design).
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 * @return string Trusted SVG markup.
 */
function ecwrd_icon( $name, $size = 16 ) {
	$paths = array(
		'menu'        => '<path d="M4 12h16"/><path d="M4 18h16"/><path d="M4 6h16"/>',
		'close'       => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-left'  => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
		'linkedin'    => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/>',
		'instagram'   => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>',
	);

	$size = (int) $size;

	if ( 'x' === $name ) {
		return sprintf(
			'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
			$size
		);
	}

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		$size,
		$paths[ $name ]
	);
}

/**
 * Attributes for a fade-up reveal.
 *
 * @param float $delay   Delay in seconds.
 * @param bool  $on_load Animate on page load rather than when scrolled into view.
 * @return string Escaped attribute string.
 */
function ecwrd_fade_attrs( $delay = 0.0, $on_load = false ) {
	$attrs = ' data-reveal="fade"';
	if ( $on_load ) {
		$attrs .= ' data-reveal-onload';
	}
	if ( $delay ) {
		$attrs .= ' style="--reveal-delay:' . esc_attr( (string) (float) $delay ) . 's"';
	}
	return $attrs;
}

/**
 * Heading that reveals line by line (each line slides up out of a mask).
 *
 * @param string|string[] $lines Lines (array) or newline-separated text.
 * @param array           $args  {
 *     Optional. Display arguments.
 *
 *     @type string $tag     Heading tag. Default 'h2'.
 *     @type string $class   Classes.
 *     @type float  $delay   Base delay in seconds.
 *     @type bool   $on_load Animate on load.
 * }
 */
function ecwrd_reveal_lines( $lines, array $args = array() ) {
	$args  = wp_parse_args(
		$args,
		array(
			'tag'     => 'h2',
			'class'   => '',
			'delay'   => 0.0,
			'on_load' => false,
		)
	);
	$lines = is_array( $lines ) ? $lines : ecwrd_lines( $lines );
	$tag   = in_array( $args['tag'], array( 'h1', 'h2', 'h3', 'p' ), true ) ? $args['tag'] : 'h2';

	if ( ! $lines ) {
		return;
	}

	printf(
		'<%1$s class="%2$s" data-reveal="lines"%3$s%4$s>',
		$tag, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelisted above.
		esc_attr( trim( 'reveal-lines ' . $args['class'] ) ),
		$args['on_load'] ? ' data-reveal-onload' : '',
		$args['delay'] ? ' style="--reveal-delay:' . esc_attr( (string) (float) $args['delay'] ) . 's"' : ''
	);

	foreach ( $lines as $i => $line ) {
		printf(
			'<span class="reveal-lines__mask"><span class="reveal-lines__line" style="--line:%d">%s</span></span>',
			(int) $i,
			esc_html( $line )
		);
	}

	printf( '</%s>', $tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- whitelisted above.
}

/**
 * Two-digit index ("01", "02"...).
 *
 * @param int $index Zero-based index.
 * @return string
 */
function ecwrd_index_label( $index ) {
	return str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT );
}

/**
 * Open a numbered "chapter" section (label column + content column).
 *
 * @param string $num    Section number.
 * @param string $label  Section label.
 * @param array  $args   {
 *     Optional. Display arguments.
 *
 *     @type bool   $border  Top border. Default true.
 *     @type string $class   Extra section classes.
 *     @type string $id      Section id attribute.
 * }
 */
function ecwrd_chapter_open( $num, $label, array $args = array() ) {
	$args    = wp_parse_args(
		$args,
		array(
			'border' => true,
			'class'  => '',
			'id'     => '',
		)
	);
	$classes = trim( 'chapter ' . ( $args['border'] ? 'chapter--border ' : '' ) . $args['class'] );
	?>
	<section class="<?php echo esc_attr( $classes ); ?>"<?php echo $args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : ''; ?>>
		<div class="chapter__grid">
			<div class="chapter__aside"<?php echo ecwrd_fade_attrs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php if ( '' !== $num ) : ?>
					<span class="chapter__num"><?php echo esc_html( $num ); ?></span>
				<?php endif; ?>
				<span class="chapter__label overline-label overline-label--muted"><?php echo esc_html( $label ); ?></span>
			</div>
			<div class="chapter__body">
	<?php
}

/**
 * Close a chapter opened with ecwrd_chapter_open().
 */
function ecwrd_chapter_close() {
	?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Portrait image with an initials fallback.
 *
 * @param array  $person Person data from ecwrd_leaders().
 * @param string $sizes  `sizes` attribute for responsive images.
 */
function ecwrd_portrait( array $person, $sizes ) {
	$name = isset( $person['name'] ) ? $person['name'] : '';
	echo '<div class="portrait">';

	if ( ! empty( $person['image_id'] ) && wp_attachment_is_image( $person['image_id'] ) ) {
		echo wp_get_attachment_image(
			$person['image_id'],
			'ecwrd-portrait',
			false,
			array(
				'class'    => 'portrait__img',
				'alt'      => $name,
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => $sizes,
			)
		);
	} elseif ( ! empty( $person['image_url'] ) ) {
		printf(
			'<img class="portrait__img" src="%s" alt="%s" loading="lazy" decoding="async" />',
			esc_url( $person['image_url'] ),
			esc_attr( $name )
		);
	} else {
		$words    = array_slice( array_values( array_filter( preg_split( '/\s+/u', $name ) ) ), 0, 2 );
		$initials = '';
		foreach ( $words as $word ) {
			$initials .= mb_substr( $word, 0, 1 );
		}
		printf( '<span class="portrait__initials" aria-hidden="true">%s</span>', esc_html( $initials ) );
	}

	echo '</div>';
}

/**
 * The site logo.
 *
 * @param string $classes Classes.
 */
function ecwrd_logo( $classes = 'site-logo' ) {
	printf(
		'<img class="%s" src="%s" alt="%s" width="442" height="60" />',
		esc_attr( $classes ),
		esc_url( ecwrd_logo_url() ),
		esc_attr( ecwrd_setting( 'council_name' ) )
	);
}

/**
 * Language switcher (AR | EN).
 *
 * @param string $context 'header', 'mobile' or 'footer'.
 */
function ecwrd_language_switcher( $context = 'header' ) {
	$current = ecwrd_lang();
	$langs   = array(
		'ar' => array(
			'label' => 'AR',
			'name'  => 'العربية',
		),
		'en' => array(
			'label' => 'EN',
			'name'  => 'English',
		),
	);
	?>
	<div class="lang-switch lang-switch--<?php echo esc_attr( $context ); ?>" role="group" aria-label="<?php echo esc_attr( 'ar' === $current ? 'اللغة' : 'Language' ); ?>">
		<?php
		$i = 0;
		foreach ( $langs as $code => $lang ) :
			if ( $i++ ) :
				?>
				<span class="lang-switch__sep" aria-hidden="true">|</span>
				<?php
			endif;
			$is_current = $code === $current;
			?>
			<a
				class="lang-switch__link<?php echo $is_current ? ' is-active' : ''; ?>"
				href="<?php echo esc_url( ecwrd_current_url_in( $code ) ); ?>"
				hreflang="<?php echo esc_attr( $code ); ?>"
				lang="<?php echo esc_attr( $code ); ?>"
				aria-label="<?php echo esc_attr( $lang['name'] ); ?>"
				<?php echo $is_current ? 'aria-current="true"' : ''; ?>
			><?php echo esc_html( $lang['label'] ); ?></a>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * UI strings that are part of the theme chrome (not editorial content).
 *
 * @param string $key String key.
 * @return string
 */
function ecwrd_ui( $key ) {
	$strings = array(
		'en' => array(
			'open_menu'  => 'Open menu',
			'close_menu' => 'Close menu',
			'primary'    => 'Primary',
			'mobile'     => 'Mobile',
			'skip'       => 'Skip to content',
		),
		'ar' => array(
			'open_menu'  => 'فتح القائمة',
			'close_menu' => 'إغلاق القائمة',
			'primary'    => 'القائمة الرئيسية',
			'mobile'     => 'قائمة الجوال',
			'skip'       => 'انتقل إلى المحتوى',
		),
	);
	$lang    = ecwrd_lang();
	return isset( $strings[ $lang ][ $key ] ) ? $strings[ $lang ][ $key ] : '';
}
