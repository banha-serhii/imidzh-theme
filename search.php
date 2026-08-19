<?php
/**
 * Search results.
 *
 * @package Imidzh
 */

get_header();

$search_query = trim( (string) get_search_query( false ) );
$has_query    = '' !== $search_query;
?>

<main id="main-content" class="main-content">
	<div class="container">
		<header class="section-header">
			<h1 class="section-title">
				<?php
				if ( $has_query ) {
					echo esc_html(
						sprintf(
							/* translators: %s: search query */
							__( 'Результати пошуку за запитом «%s»', 'imidzh' ),
							$search_query
						)
					);
				} else {
					esc_html_e( 'Пошук', 'imidzh' );
				}
				?>
			</h1>
		</header>

		<div class="search-page-form">
			<?php get_search_form(); ?>
		</div>

		<?php if ( ! $has_query ) : ?>
			<div class="search-empty content-panel">
				<p><?php esc_html_e( 'Введіть слово або фразу, щоб знайти сторінку чи новину.', 'imidzh' ); ?></p>
				<?php imidzh_the_search_helpful_links(); ?>
			</div>
		<?php elseif ( have_posts() ) : ?>
			<div class="news-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'news-card' );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<div class="search-empty content-panel">
				<p>
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: search query */
							__( 'За запитом «%s» нічого не знайдено. Перевірте правопис або спробуйте інше формулювання.', 'imidzh' ),
							$search_query
						)
					);
					?>
				</p>
				<?php imidzh_the_search_helpful_links(); ?>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
