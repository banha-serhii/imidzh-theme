<?php
/**
 * Main index / blog fallback.
 *
 * @package Imidzh
 */

get_header();
?>

<main id="main-content" class="main-content">
	<div class="container">
		<header class="section-header">
			<h1 class="section-title">
				<?php
				if ( is_home() && ! is_front_page() ) {
					single_post_title();
				} else {
					esc_html_e( 'Новини', 'imidzh' );
				}
				?>
			</h1>
		</header>

		<div class="news-grid">
			<?php if ( have_posts() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'news-card' );
				endwhile;
				?>
			<?php else : ?>
				<p><?php esc_html_e( 'Записів не знайдено.', 'imidzh' ); ?></p>
			<?php endif; ?>
		</div>

		<?php the_posts_pagination(); ?>
	</div>
</main>

<?php
get_footer();
