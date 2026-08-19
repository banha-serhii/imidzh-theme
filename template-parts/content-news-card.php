<?php
/**
 * News card partial.
 *
 * @package Imidzh
 */
?>
<article <?php post_class( 'news-card' ); ?>>
	<a class="news-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'imidzh-news' ); ?>
		<?php else : ?>
			<span><?php esc_html_e( 'Фото', 'imidzh' ); ?></span>
		<?php endif; ?>
	</a>
	<div class="news-card__body">
		<div class="news-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
			<?php
			$cats = get_the_category();
			if ( ! empty( $cats ) ) :
				echo ' | ' . esc_html( $cats[0]->name );
			endif;
			?>
		</div>
		<h3 class="news-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="news-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="news-card__link" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Читати повністю', 'imidzh' ); ?> &rarr;
		</a>
	</div>
</article>
