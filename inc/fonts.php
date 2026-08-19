<?php
/**
 * Self-hosted Source Sans 3 / Source Serif 4.
 *
 * Replaces the Google Fonts enqueue in functions.php without editing it.
 * `imidzh-style` depends on handle `imidzh-fonts`, so dequeue alone is not
 * enough — WordPress would re-print the Google URL as a dependency.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Swap Google Fonts for the local @font-face stylesheet.
 */
function imidzh_enqueue_local_fonts() {
	wp_dequeue_style( 'imidzh-fonts' );
	wp_deregister_style( 'imidzh-fonts' );

	wp_enqueue_style(
		'imidzh-fonts',
		IMIDZH_URI . '/assets/css/fonts.css',
		array(),
		IMIDZH_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'imidzh_enqueue_local_fonts', 20 );

/**
 * Drop Google Fonts preconnect hints added by imidzh_resource_hints().
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type Relation type.
 * @return array
 */
function imidzh_remove_google_fonts_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$filtered = array();
	foreach ( $urls as $url ) {
		$href = is_array( $url ) ? ( isset( $url['href'] ) ? $url['href'] : '' ) : $url;
		if ( false !== strpos( $href, 'fonts.googleapis.com' ) || false !== strpos( $href, 'fonts.gstatic.com' ) ) {
			continue;
		}
		$filtered[] = $url;
	}

	return $filtered;
}
add_filter( 'wp_resource_hints', 'imidzh_remove_google_fonts_resource_hints', 20, 2 );

/**
 * Preload the body-text face (Source Sans 3 400, Cyrillic).
 */
function imidzh_preload_body_font() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( IMIDZH_URI . '/assets/fonts/source-sans-3-cyrillic-400-normal.woff2' )
	);
}
add_action( 'wp_head', 'imidzh_preload_body_font', 1 );
