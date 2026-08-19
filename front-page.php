<?php
/**
 * Front page template — hero slider area, news, features.
 *
 * @package Imidzh
 */

get_header();

$slider_html = imidzh_get_hero_slider();
$has_slider  = (bool) $slider_html;
?>

<section
	class="hero<?php echo $has_slider ? ' hero--has-slider' : ''; ?>"
	aria-label="<?php esc_attr_e( 'Вітальний блок', 'imidzh' ); ?>"
>
	<?php if ( $has_slider ) : ?>
		<!-- Hero Slider Shortcode Integration Area -->
		<div class="hero__slider">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode HTML from trusted admin setting.
			echo $slider_html;
			?>
		</div>
	<?php else : ?>
		<!-- Fallback when no slider shortcode is active -->
		<div class="hero__fallback">
			<div class="container">
				<div class="hero__grid">
					<div>
						<h1 class="hero__title">
							<?php
							echo wp_kses(
								__( 'Простір якісної освіти та <span class="hero__title-accent">успішного майбутнього</span>', 'imidzh' ),
								array( 'span' => array( 'class' => true ) )
							);
							?>
						</h1>
						<p class="hero__subtitle">
							<?php esc_html_e( 'Ужгородський ліцей «Імідж» створює безпечне, сучасне та інклюзивне середовище для всебічного розвитку кожного учня.', 'imidzh' ); ?>
						</p>
						<div class="hero__actions">
							<a class="btn btn--accent" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
								<?php esc_html_e( 'Дізнатися більше', 'imidzh' ); ?>
							</a>
							<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/contacts/' ) ); ?>">
								<?php esc_html_e( "Зв'язатися з нами", 'imidzh' ); ?>
							</a>
						</div>
					</div>

					<?php if ( is_active_sidebar( 'hero-notice' ) ) : ?>
						<?php dynamic_sidebar( 'hero-notice' ); ?>
					<?php else : ?>
						<aside class="hero__card" aria-label="<?php esc_attr_e( 'Важливе оголошення', 'imidzh' ); ?>">
							<p class="hero__card-title"><?php esc_html_e( 'Важливе оголошення', 'imidzh' ); ?></p>
							<p class="hero__card-text">
								<?php esc_html_e( 'Триває прийом документів для вступу до 1-х та 10-х класів на новий навчальний рік.', 'imidzh' ); ?>
							</p>
							<a class="hero__card-link" href="<?php echo esc_url( home_url( '/parents/admission/' ) ); ?>">
								<?php esc_html_e( 'Графік прийому та перелік документів', 'imidzh' ); ?> &rarr;
							</a>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>

<main id="main-content" class="main-content">
	<div class="container">
		<section id="news" aria-labelledby="news-heading">
			<div class="section-header">
				<h2 id="news-heading" class="section-title"><?php esc_html_e( 'Останні новини', 'imidzh' ); ?></h2>
				<a class="section-header__link" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/news/' ) ); ?>">
					<?php esc_html_e( 'Усі новини', 'imidzh' ); ?> &rarr;
				</a>
			</div>

			<div class="news-grid">
				<?php
				$news_query = new WP_Query(
					array(
						'posts_per_page'      => 3,
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);

				if ( $news_query->have_posts() ) :
					while ( $news_query->have_posts() ) :
						$news_query->the_post();
						get_template_part( 'template-parts/content', 'news-card' );
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<p><?php esc_html_e( 'Новини з’являться незабаром.', 'imidzh' ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	</div>

	<section class="features-section" id="about" aria-labelledby="features-heading">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 id="features-heading" class="section-title"><?php esc_html_e( 'Чому обирають «Імідж»', 'imidzh' ); ?></h2>
			</div>

			<div class="features-grid">
				<?php
				$features = array(
					array(
						'icon'  => '01',
						'title' => __( 'Високий рівень освіти', 'imidzh' ),
						'text'  => __( 'Кваліфікований педагогічний колектив та сучасні методики навчання.', 'imidzh' ),
					),
					array(
						'icon'  => '02',
						'title' => __( 'Безпечне середовище', 'imidzh' ),
						'text'  => __( 'Облаштоване сучасне укриття, цілодобова охорона та відеоспостереження.', 'imidzh' ),
					),
					array(
						'icon'  => '03',
						'title' => __( 'Цифрові технології', 'imidzh' ),
						'text'  => __( 'Інтерактивні дошки, мультимедійні комплекси та сучасний комп\'ютерний клас.', 'imidzh' ),
					),
					array(
						'icon'  => '04',
						'title' => __( 'Інклюзивний простір', 'imidzh' ),
						'text'  => __( 'Безперешкодний доступ, безбар\'єрне середовище та підтримка кожного учня.', 'imidzh' ),
					),
				);

				foreach ( $features as $feature ) :
					?>
					<div class="feature-item">
						<div class="feature-item__icon" aria-hidden="true"><?php echo esc_html( $feature['icon'] ); ?></div>
						<h3 class="feature-item__title"><?php echo esc_html( $feature['title'] ); ?></h3>
						<p class="feature-item__text"><?php echo esc_html( $feature['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
