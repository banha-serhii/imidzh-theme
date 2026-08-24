<?php
/**
 * Template Name: Сторінка без бічної панелі
 * Template Post Type: page
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
			<article <?php post_class( 'content-panel content-panel--wide' ); ?> id="post-<?php the_ID(); ?>">
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
