<?php
/**
 * Fallback template (blog index, archives, search).
 *
 * The site is page-based; this keeps any other WordPress view on-brand.
 *
 * @package ECWRD
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<section class="hero hero--page">
		<div class="hero__inner">
			<div<?php echo ecwrd_fade_attrs( 0.15, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<p class="overline-label"><?php echo esc_html( ecwrd_setting( 'council_name' ) ); ?></p>
			</div>
			<?php
			ecwrd_reveal_lines(
				array( is_archive() ? wp_strip_all_tags( get_the_archive_title() ) : ( is_search() ? get_search_query() : get_bloginfo( 'name' ) ) ),
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
			<div class="rows">
				<?php if ( have_posts() ) : ?>
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article <?php post_class( 'row row--value' ); ?>>
							<span class="row__num"><?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
							<h2 class="row__title row__title--value"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="row__desc"><?php the_excerpt(); ?></div>
						</article>
					<?php endwhile; ?>
					<?php the_posts_pagination(); ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
