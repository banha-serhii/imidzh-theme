<?php
/**
 * 404 template — clear message, search, home CTA, recommended sections.
 *
 * @package Imidzh
 */

get_header();

$request_path = '';
if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$request_path = esc_url_raw( wp_unslash( (string) $_SERVER['REQUEST_URI'] ) );
	$request_path = wp_parse_url( $request_path, PHP_URL_PATH );
	$request_path = is_string( $request_path ) ? $request_path : '';
}
?>

<main id="main-content" class="main-content error-404">
	<div class="container">
		<article class="content-panel content-panel--wide error-404__panel" aria-labelledby="error-404-title">
			<p class="error-404__code" aria-hidden="true">404</p>

			<header class="entry-header error-404__header">
				<h1 id="error-404-title" class="entry-title error-404__title">
					<?php esc_html_e( 'Сторінку не знайдено', 'imidzh' ); ?>
				</h1>
			</header>

			<div class="entry-content error-404__content">
				<p>
					<?php esc_html_e( 'На жаль, такої сторінки немає або її переміщено. Перевірте адресу або скористайтеся пошуком і швидкими посиланнями нижче.', 'imidzh' ); ?>
				</p>

				<?php if ( $request_path && '/' !== $request_path ) : ?>
					<p class="error-404__path">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %s: requested URL path */
								__( 'Запитана адреса: %s', 'imidzh' ),
								$request_path
							)
						);
						?>
					</p>
				<?php endif; ?>

				<div class="error-404__actions">
					<a class="btn btn--accent" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'На головну', 'imidzh' ); ?>
					</a>
					<?php
					$news_page = get_page_by_path( 'news' );
					if ( $news_page instanceof WP_Post && 'publish' === $news_page->post_status ) :
						?>
						<a class="btn btn--primary-outline" href="<?php echo esc_url( get_permalink( $news_page ) ); ?>">
							<?php esc_html_e( 'Новини', 'imidzh' ); ?>
						</a>
					<?php endif; ?>
					<?php
					$contacts_page = get_page_by_path( 'contacts' );
					if ( $contacts_page instanceof WP_Post && 'publish' === $contacts_page->post_status ) :
						?>
						<a class="btn btn--primary-outline" href="<?php echo esc_url( get_permalink( $contacts_page ) ); ?>">
							<?php esc_html_e( 'Контакти', 'imidzh' ); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="error-404__search">
					<h2 class="error-404__subtitle"><?php esc_html_e( 'Пошук по сайту', 'imidzh' ); ?></h2>
					<?php
					get_search_form(
						array(
							'aria_label' => __( 'Пошук по сайту', 'imidzh' ),
						)
					);
					?>
				</div>

				<div class="error-404__links">
					<?php
					imidzh_the_recommended_links(
						'404',
						__( 'Можливо, вас цікавить:', 'imidzh' )
					);
					?>
				</div>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
