<?php
/**
 * Template Name: Хаб розділу
 * Template Post Type: page
 *
 * Wide hub: editable Gutenberg intro, then a live child-page grid.
 *
 * @package Imidzh
 */

get_header();
?>

<main id="main-content" class="main-content">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'content-panel content-panel--wide content-panel--hub' ); ?> id="post-<?php the_ID(); ?>">
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				<?php
				if ( function_exists( 'imidzh_the_hub_children_grid' ) ) {
					imidzh_the_hub_children_grid();
				}
				if ( function_exists( 'imidzh_the_hub_contacts_map' ) ) {
					imidzh_the_hub_contacts_map();
				}
				?>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
