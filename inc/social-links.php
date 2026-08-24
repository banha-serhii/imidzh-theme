<?php
/**
 * Social network links (Customizer + markup helpers).
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default Facebook profile URL.
 *
 * @return string
 */
function imidzh_default_facebook_url() {
	return 'https://www.facebook.com/uzhhorod.lyceum.image';
}

/**
 * Default Instagram profile URL.
 *
 * @return string
 */
function imidzh_default_instagram_url() {
	return 'https://www.instagram.com/uzhhorod.lyceum.image';
}

/**
 * Social networks configured in the Customizer.
 *
 * @return array<int, array{key:string,label:string,url:string}>
 */
function imidzh_get_social_links() {
	$networks = array(
		array(
			'key'   => 'facebook',
			'label' => __( 'Facebook', 'imidzh' ),
			'url'   => get_theme_mod( 'imidzh_facebook_url', imidzh_default_facebook_url() ),
		),
		array(
			'key'   => 'instagram',
			'label' => __( 'Instagram', 'imidzh' ),
			'url'   => get_theme_mod( 'imidzh_instagram_url', imidzh_default_instagram_url() ),
		),
	);

	$links = array();
	foreach ( $networks as $network ) {
		$url = esc_url( trim( (string) $network['url'] ) );
		if ( '' === $url ) {
			continue;
		}
		$links[] = array(
			'key'   => $network['key'],
			'label' => $network['label'],
			'url'   => $url,
		);
	}

	return $links;
}

/**
 * Inline SVG icon for a social network.
 *
 * @param string $key Network key.
 * @return string
 */
function imidzh_get_social_icon_svg( $key ) {
	$icons = array(
		'facebook'  => '<svg class="social-links__icon" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94z"/></svg>',
		'instagram' => '<svg class="social-links__icon" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4c0 3.2-2.6 5.8-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8C2 4.6 4.6 2 7.8 2zm-.2 2C5.6 4 4 5.6 4 7.6v8.8C4 18.4 5.6 20 7.6 20h8.8c2 0 3.6-1.6 3.6-3.6V7.6C20 5.6 18.4 4 16.4 4H7.6zm9.65 1.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/></svg>',
	);

	return isset( $icons[ $key ] ) ? $icons[ $key ] : '';
}

/**
 * Print social links list.
 *
 * @param string $context 'a11y' or 'footer'.
 */
function imidzh_the_social_links( $context = 'footer' ) {
	$links = imidzh_get_social_links();
	if ( empty( $links ) ) {
		return;
	}

	$context = in_array( $context, array( 'a11y', 'footer' ), true ) ? $context : 'footer';
	$class   = 'social-links social-links--' . $context;

	printf(
		'<ul class="%1$s" aria-label="%2$s">',
		esc_attr( $class ),
		esc_attr__( 'Соціальні мережі', 'imidzh' )
	);

	foreach ( $links as $link ) {
		$icon = imidzh_get_social_icon_svg( $link['key'] );
		printf(
			'<li class="social-links__item"><a class="social-links__link social-links__link--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s<span class="social-links__label">%5$s</span></a></li>',
			esc_attr( $link['key'] ),
			esc_url( $link['url'] ),
			/* translators: %s: social network name */
			esc_attr( sprintf( __( '%s (відкривається в новій вкладці)', 'imidzh' ), $link['label'] ) ),
			$icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline SVG.
			esc_html( $link['label'] )
		);
	}

	echo '</ul>';
}

/**
 * Customizer fields for social URLs.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function imidzh_social_customize_register( $wp_customize ) {
	$wp_customize->add_setting(
		'imidzh_facebook_url',
		array(
			'default'           => imidzh_default_facebook_url(),
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'imidzh_facebook_url',
		array(
			'label'       => __( 'Facebook', 'imidzh' ),
			'description' => __( 'Повне посилання на сторінку. Залиште порожнім, щоб приховати.', 'imidzh' ),
			'section'     => 'title_tagline',
			'type'        => 'url',
			'priority'    => 80,
		)
	);

	$wp_customize->add_setting(
		'imidzh_instagram_url',
		array(
			'default'           => imidzh_default_instagram_url(),
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'imidzh_instagram_url',
		array(
			'label'       => __( 'Instagram', 'imidzh' ),
			'description' => __( 'Повне посилання на профіль. Залиште порожнім, щоб приховати.', 'imidzh' ),
			'section'     => 'title_tagline',
			'type'        => 'url',
			'priority'    => 81,
		)
	);
}
add_action( 'customize_register', 'imidzh_social_customize_register' );
