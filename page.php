<?php
/**
 * Default page template — sidebar + content.
 *
 * @package Imidzh
 */

get_header();
?>

<main id="main-content" class="main-content">
	<div class="container">
		<div class="page-layout">

			<?php if ( has_nav_menu( 'sidebar' ) || is_active_sidebar( 'sidebar-1' ) ) : ?>
				<aside class="sidebar" aria-label="<?php esc_attr_e( 'Бічне меню', 'imidzh' ); ?>">
					<?php if ( has_nav_menu( 'sidebar' ) ) : ?>
						<h2 class="sidebar__title"><?php esc_html_e( 'Розділ', 'imidzh' ); ?></h2>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'sidebar',
								'menu_class'     => 'sidebar-menu',
								'container'      => false,
								'depth'          => 2,
							)
						);
						?>
					<?php elseif ( is_active_sidebar( 'sidebar-1' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					<?php endif; ?>
				</aside>
			<?php endif; ?>

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'content-panel' ); ?> id="post-<?php the_ID(); ?>">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>

		</div>
	</div>
</main>

<?php
get_footer();
