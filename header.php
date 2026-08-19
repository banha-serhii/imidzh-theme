<?php
/**
 * Header template — a11y toolbar, brand, mega-menu.
 *
 * @package Imidzh
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#24345D">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Перейти до основного вмісту', 'imidzh' ); ?></a>

<?php
$header_phone = get_theme_mod( 'imidzh_phone', '+380 (50) 777 90 36' );
$header_email = get_theme_mod( 'imidzh_email', 'uzhschool19@ukr.net' );
?>

<div class="a11y-bar" role="region" aria-label="<?php esc_attr_e( 'Панель доступності та контакти', 'imidzh' ); ?>">
	<div class="container a11y-bar__inner">
		<div class="a11y-bar__tools">
			<span class="a11y-bar__label"><?php esc_html_e( 'Панель доступності:', 'imidzh' ); ?></span>
			<div class="a11y-bar__controls">
				<button type="button" class="a11y-bar__btn" id="btn-contrast" aria-pressed="false" title="<?php esc_attr_e( 'Увімкнути високу контрастність', 'imidzh' ); ?>">
					<?php esc_html_e( 'Контраст', 'imidzh' ); ?>
				</button>
				<button type="button" class="a11y-bar__btn" id="btn-font-up" title="<?php esc_attr_e( 'Збільшити розмір шрифту', 'imidzh' ); ?>">
					A+
				</button>
				<button type="button" class="a11y-bar__btn" id="btn-font-reset" title="<?php esc_attr_e( 'Скинути розмір шрифту', 'imidzh' ); ?>">
					A
				</button>
			</div>
		</div>
		<?php if ( $header_phone || $header_email ) : ?>
			<div class="a11y-bar__contacts">
				<?php if ( $header_phone ) : ?>
					<a class="a11y-bar__contact" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $header_phone ) ); ?>">
						<?php echo esc_html( $header_phone ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $header_email ) : ?>
					<a class="a11y-bar__contact" href="mailto:<?php echo esc_attr( $header_email ); ?>">
						<?php echo esc_html( $header_email ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<header class="site-header" role="banner">
	<div class="container">
		<div class="site-header__inner">

			<a class="brand<?php echo imidzh_has_logo() ? ' brand--logo' : ' brand--mark'; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' — ' . __( 'Головна', 'imidzh' ) ); ?>">
				<?php imidzh_the_logo( 'header' ); ?>
				<span class="brand__text">
					<span class="brand__title"><?php bloginfo( 'name' ); ?></span>
					<?php
					$desc = get_bloginfo( 'description', 'display' );
					if ( $desc ) :
						?>
						<span class="brand__tagline"><?php echo esc_html( $desc ); ?></span>
					<?php endif; ?>
				</span>
			</a>

			<button
				type="button"
				class="menu-toggle"
				id="menu-toggle"
				aria-expanded="false"
				aria-controls="primary-nav"
				aria-label="<?php esc_attr_e( 'Відкрити меню', 'imidzh' ); ?>"
			>
				<span class="menu-toggle__icon" aria-hidden="true"></span>
			</button>

			<nav id="primary-nav" class="primary-nav" aria-label="<?php esc_attr_e( 'Головна навігація', 'imidzh' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'mega-menu',
						'menu_class'     => 'mega-menu',
						'container'      => false,
						'fallback_cb'    => 'imidzh_fallback_menu',
						'depth'          => 3,
						'walker'         => new Imidzh_Mega_Menu_Walker(),
					)
				);
				?>
			</nav>

		</div>
	</div>
</header>
