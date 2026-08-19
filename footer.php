<?php
/**
 * Footer template.
 *
 * @package Imidzh
 */

$phone   = get_theme_mod( 'imidzh_phone', '+380 (50) 777 90 36' );
$email   = get_theme_mod( 'imidzh_email', 'uzhschool19@ukr.net' );
$address = get_theme_mod( 'imidzh_address', 'м. Ужгород, Закарпатська обл.' );
?>

<footer class="site-footer" id="contacts" role="contentinfo">
	<div class="container">
		<div class="footer-grid">

			<div class="footer-col">
				<a class="footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' — ' . __( 'Головна', 'imidzh' ) ); ?>">
					<?php imidzh_the_logo( 'footer' ); ?>
					<h2 class="footer-brand__name"><?php bloginfo( 'name' ); ?></h2>
				</a>
				<p class="footer-col__text">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: site name */
							__( '%s Ужгородської міської ради Закарпатської області.', 'imidzh' ),
							get_bloginfo( 'name' )
						)
					);
					?>
				</p>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Навігація', 'imidzh' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'footer-links',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Прозорість', 'imidzh' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_2',
						'menu_class'     => 'footer-links',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<div class="footer-col">
				<h2 class="footer-col__title"><?php esc_html_e( 'Контакти', 'imidzh' ); ?></h2>
				<ul class="footer-contacts">
					<?php if ( $address ) : ?>
						<li><?php echo esc_html( $address ); ?></li>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<li>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>">
								<?php echo esc_html( $phone ); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<li>
							<a href="mailto:<?php echo esc_attr( $email ); ?>">
								<?php echo esc_html( $email ); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>

		</div>

		<div class="footer-bottom">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<?php bloginfo( 'name' ); ?>.
				<?php esc_html_e( 'Всі права захищено. Офіційний веб-сайт.', 'imidzh' ); ?>
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
