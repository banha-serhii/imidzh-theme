<?php
/**
 * Ліцей «Імідж» — theme functions.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

define( 'IMIDZH_VERSION', '1.1.0' );
define( 'IMIDZH_DIR', get_template_directory() );
define( 'IMIDZH_URI', get_template_directory_uri() );

require_once IMIDZH_DIR . '/inc/class-mega-menu-walker.php';
if ( file_exists( IMIDZH_DIR . '/inc/fonts.php' ) ) {
	require_once IMIDZH_DIR . '/inc/fonts.php';
}
if ( file_exists( IMIDZH_DIR . '/inc/menu-setup.php' ) ) {
	require_once IMIDZH_DIR . '/inc/menu-setup.php';
}
if ( file_exists( IMIDZH_DIR . '/inc/search.php' ) ) {
	require_once IMIDZH_DIR . '/inc/search.php';
}
if ( file_exists( IMIDZH_DIR . '/inc/yoast-seo.php' ) ) {
	require_once IMIDZH_DIR . '/inc/yoast-seo.php';
}

/**
 * Fallback primary menu when no menu is assigned.
 */
if ( ! function_exists( 'imidzh_fallback_menu' ) ) {
	function imidzh_fallback_menu() {
		$items = array(
			array( 'url' => home_url( '/about/' ), 'label' => __( 'Про ліцей', 'imidzh' ) ),
			array( 'url' => home_url( '/transparency/' ), 'label' => __( 'Прозорість та звітність', 'imidzh' ) ),
			array( 'url' => home_url( '/education/' ), 'label' => __( 'Освітній процес', 'imidzh' ) ),
			array( 'url' => home_url( '/parents/' ), 'label' => __( 'Вступникам та батькам', 'imidzh' ) ),
			array( 'url' => home_url( '/safety/' ), 'label' => __( 'Безпека та захист', 'imidzh' ) ),
			array( 'url' => home_url( '/teachers/' ), 'label' => __( 'Педагогам', 'imidzh' ) ),
			array( 'url' => home_url( '/news/' ), 'label' => __( 'Новини', 'imidzh' ) ),
		);

		echo '<ul id="mega-menu" class="mega-menu">';
		foreach ( $items as $item ) {
			printf(
				'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
				esc_url( $item['url'] ),
				esc_html( $item['label'] )
			);
		}
		echo '</ul>';
	}
}

/**
 * Theme setup.
 */
function imidzh_setup() {
	load_theme_textdomain( 'imidzh', IMIDZH_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 160,
			'width'       => 160,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus(
		array(
			'primary'  => __( 'Головне меню (Mega Menu)', 'imidzh' ),
			'sidebar'  => __( 'Бічне меню внутрішніх сторінок', 'imidzh' ),
			'footer'   => __( 'Меню в підвалі (Навігація)', 'imidzh' ),
			'footer_2' => __( 'Меню в підвалі (Прозорість)', 'imidzh' ),
		)
	);

	add_image_size( 'imidzh-news', 640, 360, true );
}
add_action( 'after_setup_theme', 'imidzh_setup' );

/**
 * Register widget areas.
 */
function imidzh_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Бічна панель сторінки', 'imidzh' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Віджети для внутрішніх сторінок.', 'imidzh' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="sidebar__title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Оголошення в Hero (fallback)', 'imidzh' ),
			'id'            => 'hero-notice',
			'description'   => __( 'Коротке оголошення, якщо слайдер не активний.', 'imidzh' ),
			'before_widget' => '<div id="%1$s" class="hero__card widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="hero__card-title">',
			'after_title'   => '</p>',
		)
	);
}
add_action( 'widgets_init', 'imidzh_widgets_init' );

/**
 * Enqueue styles and scripts.
 */
function imidzh_enqueue_assets() {
	wp_enqueue_style(
		'imidzh-fonts',
		'https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700;800&family=Source+Serif+4:opsz,wght@8..60,600;8..60,700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'imidzh-style',
		get_stylesheet_uri(),
		array( 'imidzh-fonts' ),
		IMIDZH_VERSION
	);

	wp_enqueue_script(
		'imidzh-main',
		IMIDZH_URI . '/assets/js/main.js',
		array(),
		IMIDZH_VERSION,
		true
	);

	wp_localize_script(
		'imidzh-main',
		'imidzhTheme',
		array(
			'i18n' => array(
				'openMenu'  => __( 'Відкрити меню', 'imidzh' ),
				'closeMenu' => __( 'Закрити меню', 'imidzh' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'imidzh_enqueue_assets' );

/**
 * Preconnect for Google Fonts (performance).
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type Relation type.
 * @return array
 */
function imidzh_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'imidzh_resource_hints', 10, 2 );

/**
 * Hero slider shortcode output.
 *
 * Customize via Customizer or filter `imidzh_hero_slider_shortcode`.
 * Examples: [hero_slider], [smartslider3 slider="2"], Gutenberg block HTML.
 *
 * @return string
 */
function imidzh_get_hero_slider() {
	$shortcode = apply_filters(
		'imidzh_hero_slider_shortcode',
		get_theme_mod( 'imidzh_hero_shortcode', '[hero_slider]' )
	);

	$shortcode = trim( (string) $shortcode );
	if ( '' === $shortcode ) {
		return '';
	}

	$output = do_shortcode( $shortcode );

	// If shortcode did not resolve (returned as-is or empty), treat as inactive.
	if ( '' === trim( wp_strip_all_tags( $output ) ) || $output === $shortcode ) {
		return '';
	}

	return $output;
}

/**
 * Whether a resolved hero slider is available.
 *
 * @return bool
 */
function imidzh_has_hero_slider() {
	return (bool) imidzh_get_hero_slider();
}

/**
 * Customizer: hero shortcode field.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function imidzh_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'imidzh_hero',
		array(
			'title'    => __( 'Hero / Слайдер', 'imidzh' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'imidzh_hero_shortcode',
		array(
			'default'           => '[hero_slider]',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'imidzh_hero_shortcode',
		array(
			'label'       => __( 'Shortcode слайдера', 'imidzh' ),
			'description' => __( 'Напр. [smartslider3 slider="2"]. Залиште порожнім або невалідним — покажеться fallback.', 'imidzh' ),
			'section'     => 'imidzh_hero',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'imidzh_phone',
		array(
			'default'           => '+380 (50) 777 90 36',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'imidzh_phone',
		array(
			'label'   => __( 'Телефон у підвалі', 'imidzh' ),
			'section' => 'title_tagline',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'imidzh_email',
		array(
			'default'           => 'uzhschool19@ukr.net',
			'sanitize_callback' => 'sanitize_email',
		)
	);

	$wp_customize->add_control(
		'imidzh_email',
		array(
			'label'   => __( 'Email у підвалі', 'imidzh' ),
			'section' => 'title_tagline',
			'type'    => 'email',
		)
	);

	$wp_customize->add_setting(
		'imidzh_address',
		array(
			'default'           => 'м. Ужгород, Закарпатська обл.',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'imidzh_address',
		array(
			'label'   => __( 'Адреса у підвалі', 'imidzh' ),
			'section' => 'title_tagline',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'imidzh_customize_register' );

/**
 * Add body classes for a11y persistence hook.
 *
 * @param array $classes Body classes.
 * @return array
 */
function imidzh_body_classes( $classes ) {
	$classes[] = 'imidzh-theme';
	if ( is_front_page() ) {
		$classes[] = 'imidzh-front';
	}
	return $classes;
}
add_filter( 'body_class', 'imidzh_body_classes' );

/**
 * Excerpt length for news cards.
 *
 * @param int $length Default length.
 * @return int
 */
function imidzh_excerpt_length( $length ) {
	return 22;
}
add_filter( 'excerpt_length', 'imidzh_excerpt_length' );

/**
 * Excerpt more string.
 *
 * @return string
 */
function imidzh_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'imidzh_excerpt_more' );

/**
 * Whether the bundled theme emblem exists.
 *
 * @return bool
 */
function imidzh_has_theme_logo() {
	return file_exists( IMIDZH_DIR . '/assets/img/logo.png' ) || file_exists( IMIDZH_DIR . '/assets/img/logo.jpg' );
}

/**
 * Whether any brand emblem is available (Customizer or theme file).
 *
 * @return bool
 */
function imidzh_has_logo() {
	return has_custom_logo() || imidzh_has_theme_logo();
}

/**
 * Output optimized logo markup for header or footer.
 *
 * Adjacent site name is visible, so img alt stays empty (WCAG).
 *
 * @param string $context 'header' or 'footer'.
 */
function imidzh_the_logo( $context = 'header' ) {
	$is_header = ( 'footer' !== $context );
	$class     = $is_header ? 'brand__logo' : 'footer-brand__logo';
	$width     = $is_header ? 80 : 64;
	$height    = $is_header ? 80 : 64;
	$extra     = $is_header ? ' fetchpriority="high"' : ' loading="lazy"';

	$img_attrs = array(
		'class'    => $class,
		'alt'      => '',
		'decoding' => 'async',
		'width'    => $width,
		'height'   => $height,
	);

	if ( $is_header ) {
		$img_attrs['fetchpriority'] = 'high';
	} else {
		$img_attrs['loading'] = 'lazy';
	}

	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		echo wp_get_attachment_image( $logo_id, 'full', false, $img_attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	$png  = IMIDZH_DIR . '/assets/img/logo.png';
	$webp = IMIDZH_DIR . '/assets/img/logo.webp';
	$jpg  = IMIDZH_DIR . '/assets/img/logo.jpg';

	if ( file_exists( $png ) || file_exists( $jpg ) ) {
		$src = file_exists( $png )
			? IMIDZH_URI . '/assets/img/logo.png'
			: IMIDZH_URI . '/assets/img/logo.jpg';

		echo '<picture>';
		if ( file_exists( $webp ) ) {
			printf(
				'<source srcset="%s" type="image/webp">',
				esc_url( IMIDZH_URI . '/assets/img/logo.webp' )
			);
		}
		printf(
			'<img src="%1$s" class="%2$s" alt="" width="%3$d" height="%4$d" decoding="async"%5$s>',
			esc_url( $src ),
			esc_attr( $class ),
			(int) $width,
			(int) $height,
			$extra
		);
		echo '</picture>';
		return;
	}

	echo '<span class="brand__mark" aria-hidden="true">І</span>';
}

/**
 * Fallback favicon / Apple touch icon when no Site Icon is set.
 */
function imidzh_fallback_icons() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	$favicon = IMIDZH_DIR . '/assets/img/favicon-32.png';
	$apple   = IMIDZH_DIR . '/assets/img/apple-touch-icon.png';

	if ( file_exists( $favicon ) ) {
		printf(
			'<link rel="icon" href="%s" type="image/png" sizes="32x32">' . "\n",
			esc_url( IMIDZH_URI . '/assets/img/favicon-32.png' )
		);
	}

	if ( file_exists( $apple ) ) {
		printf(
			'<link rel="apple-touch-icon" href="%s" sizes="180x180">' . "\n",
			esc_url( IMIDZH_URI . '/assets/img/apple-touch-icon.png' )
		);
	}
}
add_action( 'wp_head', 'imidzh_fallback_icons', 2 );

/**
 * Preload the theme emblem on first paint.
 */
function imidzh_preload_logo() {
	if ( has_custom_logo() ) {
		return;
	}

	$webp = IMIDZH_DIR . '/assets/img/logo.webp';
	if ( file_exists( $webp ) ) {
		printf(
			'<link rel="preload" as="image" href="%s" type="image/webp">' . "\n",
			esc_url( IMIDZH_URI . '/assets/img/logo.webp' )
		);
	}
}
add_action( 'wp_head', 'imidzh_preload_logo', 1 );
