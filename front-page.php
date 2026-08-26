<?php
/**
 * Front page — hero, quick links, news, gallery, features, transparency, contacts/map.
 *
 * @package Imidzh
 */

get_header();

$slider_html   = imidzh_get_hero_slider();
$has_slider    = (bool) $slider_html;
$hero_image_id = absint( get_theme_mod( 'imidzh_home_hero_image', 0 ) );
$hero_bg_url   = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'full' ) : '';

$notice_title = get_theme_mod( 'imidzh_hero_notice_title', __( 'Важливе оголошення', 'imidzh' ) );
$notice_text  = get_theme_mod( 'imidzh_hero_notice_text', __( 'Триває прийом документів для вступу до 1-х та 10-х класів на новий навчальний рік.', 'imidzh' ) );
$notice_label = get_theme_mod( 'imidzh_hero_notice_link_label', __( 'Графік прийому та перелік документів', 'imidzh' ) );
$notice_link  = imidzh_get_hero_notice_link();

$phone   = get_theme_mod( 'imidzh_phone', '+380 (50) 777 90 36' );
$email   = get_theme_mod( 'imidzh_email', 'uzhschool19@ukr.net' );
$address = get_theme_mod( 'imidzh_address', 'м. Ужгород, Закарпатська обл.' );
$hours   = get_theme_mod( 'imidzh_hours', __( 'Пн–Пт: 8:00–17:00', 'imidzh' ) );
$map_url = imidzh_sanitize_map_embed_url( get_theme_mod( 'imidzh_map_embed_url', '' ) );
$contact_image_id = absint( get_theme_mod( 'imidzh_home_contact_image', 0 ) );

$gallery_ids = imidzh_get_home_gallery_ids();
$quick_links = imidzh_get_home_quick_links();
$features    = imidzh_get_home_features();
$transparency_links = imidzh_get_home_transparency_links();

$hero_fallback_style = $hero_bg_url
	? ' style="--imidzh-hero-photo: url(' . esc_url( $hero_bg_url ) . ');"'
	: '';
?>

<section
	class="hero<?php echo $has_slider ? ' hero--has-slider' : ''; ?><?php echo ( ! $has_slider && $hero_bg_url ) ? ' hero--has-photo' : ''; ?>"
	aria-label="<?php esc_attr_e( 'Вітальний блок', 'imidzh' ); ?>"
	<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above via esc_url in assignment.
	echo $hero_fallback_style;
	?>
>
	<?php if ( $has_slider ) : ?>
		<div class="hero__slider">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode HTML from trusted admin setting.
			echo $slider_html;
			?>
		</div>
	<?php else : ?>
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
							<a class="btn btn--accent" href="<?php echo esc_url( $notice_link ); ?>">
								<?php esc_html_e( 'Правила прийому', 'imidzh' ); ?>
							</a>
							<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/contacts/' ) ); ?>">
								<?php esc_html_e( 'Контакти', 'imidzh' ); ?>
							</a>
						</div>
					</div>

					<?php if ( is_active_sidebar( 'hero-notice' ) ) : ?>
						<?php dynamic_sidebar( 'hero-notice' ); ?>
					<?php elseif ( $notice_title || $notice_text ) : ?>
						<aside class="hero__card" aria-label="<?php esc_attr_e( 'Важливе оголошення', 'imidzh' ); ?>">
							<?php if ( $notice_title ) : ?>
								<p class="hero__card-title"><?php echo esc_html( $notice_title ); ?></p>
							<?php endif; ?>
							<?php if ( $notice_text ) : ?>
								<p class="hero__card-text"><?php echo esc_html( $notice_text ); ?></p>
							<?php endif; ?>
							<?php if ( $notice_link && $notice_label ) : ?>
								<a class="hero__card-link" href="<?php echo esc_url( $notice_link ); ?>">
									<?php echo esc_html( $notice_label ); ?> &rarr;
								</a>
							<?php endif; ?>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>

<main id="main-content" class="main-content main-content--front">

	<section class="home-quick" aria-labelledby="quick-heading">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 id="quick-heading" class="section-title"><?php esc_html_e( 'Розділи сайту', 'imidzh' ); ?></h2>
			</div>
			<ul class="home-quick__grid">
				<?php foreach ( $quick_links as $item ) : ?>
					<li>
						<a class="home-quick__card home-quick__card--<?php echo esc_attr( $item['slug'] ); ?>" href="<?php echo esc_url( $item['url'] ); ?>">
							<span class="home-quick__title"><?php echo esc_html( $item['title'] ); ?></span>
							<span class="home-quick__text"><?php echo esc_html( $item['text'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section id="news" class="home-news" aria-labelledby="news-heading">
		<div class="container">
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
					<div class="home-empty content-panel">
						<p><?php esc_html_e( 'Новини з’являться незабаром. Додайте записи в розділі «Новини».', 'imidzh' ); ?></p>
						<?php if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) : ?>
							<a class="btn btn--primary-outline" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>">
								<?php esc_html_e( 'Додати новину', 'imidzh' ); ?>
							</a>
						<?php else : ?>
							<a class="btn btn--primary-outline" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">
								<?php esc_html_e( 'Перейти до новин', 'imidzh' ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $gallery_ids ) ) : ?>
		<section class="home-gallery" aria-labelledby="gallery-heading">
			<div class="container">
				<div class="section-header section-header--center">
					<h2 id="gallery-heading" class="section-title"><?php esc_html_e( 'Життя ліцею', 'imidzh' ); ?></h2>
				</div>
				<div class="home-gallery__grid">
					<?php foreach ( $gallery_ids as $gid ) : ?>
						<figure class="home-gallery__item">
							<?php
							echo imidzh_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$gid,
								'imidzh-news',
								array(
									'loading' => 'lazy',
									'alt'     => '',
								)
							);
							?>
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="features-section" id="about" aria-labelledby="features-heading">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 id="features-heading" class="section-title"><?php esc_html_e( 'Чому обирають «Імідж»', 'imidzh' ); ?></h2>
			</div>
			<div class="features-grid">
				<?php foreach ( $features as $feature ) : ?>
					<a class="feature-item feature-item--link" href="<?php echo esc_url( $feature['url'] ); ?>">
						<div class="feature-item__icon" aria-hidden="true"><?php echo esc_html( $feature['icon'] ); ?></div>
						<h3 class="feature-item__title"><?php echo esc_html( $feature['title'] ); ?></h3>
						<p class="feature-item__text"><?php echo esc_html( $feature['text'] ); ?></p>
						<span class="feature-item__more"><?php esc_html_e( 'Детальніше', 'imidzh' ); ?> &rarr;</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="home-transparency" aria-labelledby="transparency-heading">
		<div class="container">
			<div class="section-header">
				<h2 id="transparency-heading" class="section-title"><?php esc_html_e( 'Прозорість', 'imidzh' ); ?></h2>
				<a class="section-header__link" href="<?php echo esc_url( home_url( '/transparency/' ) ); ?>">
					<?php esc_html_e( 'Усі документи', 'imidzh' ); ?> &rarr;
				</a>
			</div>
			<p class="home-transparency__lead">
				<?php esc_html_e( 'Ключові матеріали згідно зі ст. 30 Закону України «Про освіту».', 'imidzh' ); ?>
			</p>
			<ul class="home-transparency__grid">
				<?php foreach ( $transparency_links as $t_link ) : ?>
					<li>
						<a class="home-transparency__card" href="<?php echo esc_url( $t_link['url'] ); ?>">
							<?php echo esc_html( $t_link['title'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="home-contacts" id="home-contacts" aria-labelledby="contacts-heading">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 id="contacts-heading" class="section-title"><?php esc_html_e( 'Контакти та розташування', 'imidzh' ); ?></h2>
			</div>
			<div class="home-contacts__grid">
				<div class="home-contacts__info content-panel">
					<ul class="home-contacts__list">
						<?php if ( $address ) : ?>
							<li>
								<span class="home-contacts__label"><?php esc_html_e( 'Адреса', 'imidzh' ); ?></span>
								<span class="home-contacts__value"><?php echo esc_html( $address ); ?></span>
							</li>
						<?php endif; ?>
						<?php if ( $hours ) : ?>
							<li>
								<span class="home-contacts__label"><?php esc_html_e( 'Графік роботи', 'imidzh' ); ?></span>
								<span class="home-contacts__value"><?php echo esc_html( $hours ); ?></span>
							</li>
						<?php endif; ?>
						<?php if ( $phone ) : ?>
							<li>
								<span class="home-contacts__label"><?php esc_html_e( 'Телефон', 'imidzh' ); ?></span>
								<a class="home-contacts__value" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>">
									<?php echo esc_html( $phone ); ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( $email ) : ?>
							<li>
								<span class="home-contacts__label"><?php esc_html_e( 'Email', 'imidzh' ); ?></span>
								<a class="home-contacts__value" href="mailto:<?php echo esc_attr( $email ); ?>">
									<?php echo esc_html( $email ); ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
					<div class="home-contacts__actions">
						<a class="btn btn--accent" href="<?php echo esc_url( home_url( '/contacts/' ) ); ?>">
							<?php esc_html_e( 'Сторінка контактів', 'imidzh' ); ?>
						</a>
						<?php imidzh_the_social_links( 'footer' ); ?>
					</div>
				</div>

				<div class="home-contacts__media">
					<?php if ( $map_url ) : ?>
						<iframe
							class="home-contacts__map"
							src="<?php echo esc_url( $map_url ); ?>"
							title="<?php esc_attr_e( 'Мапа розташування ліцею', 'imidzh' ); ?>"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							allowfullscreen
						></iframe>
					<?php elseif ( $contact_image_id ) : ?>
						<figure class="home-contacts__photo">
							<?php
							echo imidzh_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$contact_image_id,
								'large',
								array(
									'loading' => 'lazy',
									'alt'     => __( 'Територія ліцею', 'imidzh' ),
								)
							);
							?>
						</figure>
					<?php else : ?>
						<div class="home-contacts__placeholder content-panel">
							<p><?php esc_html_e( 'Додайте URL мапи або фото в «Зовнішній вигляд → Налаштувати → Головна сторінка».', 'imidzh' ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
