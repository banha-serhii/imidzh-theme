<?php
/**
 * Site search: assets, SearchAction schema, helpful empty-state links.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Desktop / drawer breakpoint — keep in sync with assets/css/search.css
 * and assets/js/search.js (same value as mega-menu: 1100px).
 */
define( 'IMIDZH_SEARCH_DESKTOP_MIN', 1100 );

/**
 * Enqueue search stylesheet and behaviour.
 */
function imidzh_enqueue_search_assets() {
	$style_deps = array( 'imidzh-style' );
	if ( wp_style_is( 'imidzh-mega-menu', 'registered' ) || wp_style_is( 'imidzh-mega-menu', 'enqueued' ) ) {
		$style_deps[] = 'imidzh-mega-menu';
	}

	wp_enqueue_style(
		'imidzh-search',
		IMIDZH_URI . '/assets/css/search.css',
		$style_deps,
		IMIDZH_VERSION
	);

	wp_enqueue_script(
		'imidzh-search',
		IMIDZH_URI . '/assets/js/search.js',
		array(),
		IMIDZH_VERSION,
		true
	);

	wp_localize_script(
		'imidzh-search',
		'imidzhSearch',
		array(
			'desktopMin' => (int) IMIDZH_SEARCH_DESKTOP_MIN,
			'i18n'       => array(
				'open'  => __( 'Відкрити пошук', 'imidzh' ),
				'close' => __( 'Закрити пошук', 'imidzh' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'imidzh_enqueue_search_assets', 25 );

/**
 * SearchAction urlTemplate with literal {search_term_string} (must not be encoded).
 *
 * @return string
 */
function imidzh_search_action_target() {
	return untrailingslashit( home_url( '/' ) ) . '/?s={search_term_string}';
}

/**
 * SearchAction payload shared by JSON-LD and Yoast WebSite graph.
 *
 * @return array
 */
function imidzh_search_action_schema() {
	return array(
		'@type'       => 'SearchAction',
		'target'      => array(
			'@type'       => 'EntryPoint',
			'urlTemplate' => imidzh_search_action_target(),
		),
		'query-input' => array(
			'@type'         => 'PropertyValueSpecification',
			'valueRequired' => true,
			'valueName'     => 'search_term_string',
		),
	);
}

/**
 * Standalone WebSite + SearchAction when Yoast is not outputting schema.
 */
function imidzh_search_action_jsonld() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		return;
	}

	$data = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'url'             => home_url( '/' ),
		'name'            => get_bloginfo( 'name', 'display' ),
		'potentialAction' => imidzh_search_action_schema(),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG ) . "</script>\n";
}
add_action( 'wp_head', 'imidzh_search_action_jsonld', 20 );

/**
 * Attach SearchAction to Yoast WebSite graph without adding Organization.
 *
 * @param array $data WebSite schema piece.
 * @return array
 */
function imidzh_yoast_website_search_action( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	if ( empty( $data['potentialAction'] ) ) {
		$data['potentialAction'] = imidzh_search_action_schema();
	}

	return $data;
}
add_filter( 'wpseo_schema_website', 'imidzh_yoast_website_search_action' );

/**
 * Helpful internal links for empty / zero search results.
 *
 * @return array<int, array{url: string, label: string}>
 */
function imidzh_get_search_helpful_links() {
	$candidates = array(
		'transparency' => __( 'Прозорість', 'imidzh' ),
		'news'         => __( 'Новини', 'imidzh' ),
	);

	$links = array();

	foreach ( $candidates as $slug => $label ) {
		$page = get_page_by_path( $slug );
		if ( ! $page instanceof WP_Post || 'publish' !== $page->post_status ) {
			continue;
		}

		$links[] = array(
			'url'   => get_permalink( $page ),
			'label' => $label,
		);
	}

	return $links;
}

/**
 * Print helpful links under empty / zero search results.
 */
function imidzh_the_search_helpful_links() {
	$links = imidzh_get_search_helpful_links();
	if ( empty( $links ) ) {
		return;
	}
	?>
	<p class="search-empty__lead"><?php esc_html_e( 'Корисні розділи:', 'imidzh' ); ?></p>
	<ul class="search-empty__links">
		<?php foreach ( $links as $link ) : ?>
			<li>
				<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}
