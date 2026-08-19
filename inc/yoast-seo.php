<?php
/**
 * Yoast-safe SEO defaults and EducationalOrganization schema.
 *
 * Yoast remains the source of truth for title, canonical, Open Graph, and robots.
 * This module never prints <title>, rel=canonical, or og:* tags, never noindexes
 * the site or /transparency/, and does not disable the Yoast sitemap.
 * SearchAction lives in inc/search.php — not duplicated here.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/** Stable Organization @id fragment — same node everywhere (Yoast convention). */
define( 'IMIDZH_SCHEMA_ORG_HASH', '#organization' );

/** Schema.org inLanguage for this single-language Ukrainian site. */
define( 'IMIDZH_SCHEMA_IN_LANGUAGE', 'uk-UA' );

/**
 * Whether Yoast SEO is available.
 *
 * @return bool
 */
function imidzh_is_yoast_active() {
	return defined( 'WPSEO_VERSION' ) || function_exists( 'YoastSEO' );
}

/**
 * Whether a SEO/schema string should be treated as empty.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function imidzh_yoast_value_is_empty( $value ) {
	if ( null === $value ) {
		return true;
	}
	if ( is_array( $value ) ) {
		return array() === $value;
	}
	if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
		return true;
	}
	return '' === trim( wp_strip_all_tags( (string) $value ) );
}

/**
 * Canonical Organization @id (home URL + #organization).
 *
 * Matches Yoast’s `#organization` fragment so graph nodes merge instead of duplicating.
 *
 * @param string $site_url Optional site URL from Yoast context.
 * @return string
 */
function imidzh_schema_organization_id( $site_url = '' ) {
	if ( '' === $site_url ) {
		$site_url = home_url( '/' );
	}

	return trailingslashit( $site_url ) . IMIDZH_SCHEMA_ORG_HASH;
}

/**
 * Normalize a phone number for schema.org telephone.
 *
 * @param string $phone Display phone.
 * @return string
 */
function imidzh_schema_telephone( $phone ) {
	return (string) preg_replace( '/[^\d+]/', '', (string) $phone );
}

/**
 * Institution contact values from Customizer, with school defaults.
 *
 * @return array{name: string, phone: string, email: string}
 */
function imidzh_schema_contact_details() {
	$name  = get_bloginfo( 'name', 'display' );
	$phone = get_theme_mod( 'imidzh_phone', '+380 (50) 777 90 36' );
	$email = get_theme_mod( 'imidzh_email', 'uzhschool19@ukr.net' );

	return array(
		'name'  => imidzh_yoast_value_is_empty( $name ) ? __( 'Ліцей «Імідж»', 'imidzh' ) : $name,
		'phone' => is_string( $phone ) ? trim( $phone ) : '',
		'email' => is_string( $email ) ? sanitize_email( $email ) : '',
	);
}

/**
 * EducationalOrganization payload (no @context — caller adds it for raw JSON-LD).
 *
 * @param string $org_id Organization @id.
 * @return array<string, mixed>
 */
function imidzh_get_educational_organization_schema( $org_id = '' ) {
	if ( '' === $org_id ) {
		$org_id = imidzh_schema_organization_id();
	}

	$contact = imidzh_schema_contact_details();

	$schema = array(
		'@type'         => array( 'EducationalOrganization', 'HighSchool' ),
		'@id'           => $org_id,
		'name'          => $contact['name'],
		'alternateName' => 'Імідж',
		'url'           => home_url( '/' ),
		'inLanguage'    => IMIDZH_SCHEMA_IN_LANGUAGE,
		'address'       => array(
			'@type'           => 'PostalAddress',
			'addressLocality' => 'Ужгород',
			'addressRegion'   => 'Закарпатська область',
			'addressCountry'  => 'UA',
		),
	);

	$tel = imidzh_schema_telephone( $contact['phone'] );
	if ( '' !== $tel ) {
		$schema['telephone'] = $tel;
	}

	if ( is_email( $contact['email'] ) ) {
		$schema['email'] = $contact['email'];
	}

	return $schema;
}

/**
 * Ensure EducationalOrganization / HighSchool appear on @type without dropping Organization.
 *
 * @param mixed $type Existing @type.
 * @return string|array<int, string>
 */
function imidzh_schema_with_educational_types( $type ) {
	$types = array();
	foreach ( (array) $type as $item ) {
		$item = is_string( $item ) ? trim( $item ) : '';
		if ( '' !== $item && ! in_array( $item, $types, true ) ) {
			$types[] = $item;
		}
	}

	foreach ( array( 'EducationalOrganization', 'HighSchool' ) as $required ) {
		if ( ! in_array( $required, $types, true ) ) {
			$types[] = $required;
		}
	}

	return 1 === count( $types ) ? $types[0] : $types;
}

/**
 * Fill empty institution fields; never overwrite non-empty Yoast values.
 *
 * @param mixed $existing Existing graph piece.
 * @param mixed $incoming Optional extra piece to fold in (existing wins).
 * @return array<string, mixed>
 */
function imidzh_merge_organization_schema( $existing, $incoming = array() ) {
	if ( ! is_array( $existing ) ) {
		$existing = array();
	}
	if ( ! is_array( $incoming ) ) {
		$incoming = array();
	}

	$org_id = '';
	if ( ! empty( $existing['@id'] ) && is_string( $existing['@id'] ) ) {
		$org_id = $existing['@id'];
	} elseif ( ! empty( $incoming['@id'] ) && is_string( $incoming['@id'] ) ) {
		$org_id = $incoming['@id'];
	}

	$defaults = imidzh_get_educational_organization_schema( $org_id );
	$merged   = $existing;

	$merged['@type'] = imidzh_schema_with_educational_types(
		array_merge(
			(array) ( $existing['@type'] ?? array() ),
			(array) ( $incoming['@type'] ?? array() ),
			(array) $defaults['@type']
		)
	);

	if ( empty( $merged['@id'] ) ) {
		$merged['@id'] = $defaults['@id'];
	}

	foreach ( $defaults as $key => $value ) {
		if ( '@type' === $key || '@id' === $key ) {
			continue;
		}
		if ( ! imidzh_yoast_value_is_empty( $merged[ $key ] ?? null ) ) {
			continue;
		}
		$from_incoming = $incoming[ $key ] ?? null;
		$merged[ $key ] = imidzh_yoast_value_is_empty( $from_incoming ) ? $value : $from_incoming;
	}

	return $merged;
}

/**
 * Whether a schema graph piece is an organization-like entity.
 *
 * @param array<string, mixed> $piece Graph node.
 * @return bool
 */
function imidzh_schema_piece_is_organization( $piece ) {
	if ( ! is_array( $piece ) ) {
		return false;
	}

	$id     = isset( $piece['@id'] ) ? (string) $piece['@id'] : '';
	$hash   = IMIDZH_SCHEMA_ORG_HASH;
	$id_end = substr( $id, -strlen( $hash ) );
	if ( '' !== $id && $hash === $id_end ) {
		return true;
	}

	$org_types = array( 'Organization', 'EducationalOrganization', 'HighSchool' );
	foreach ( (array) ( $piece['@type'] ?? array() ) as $type ) {
		if ( in_array( $type, $org_types, true ) ) {
			return true;
		}
	}

	return false;
}

/**
 * WebSite node for the no-Yoast fallback (SearchAction stays in inc/search.php).
 *
 * @param string $org_id Organization @id.
 * @return array<string, mixed>
 */
function imidzh_get_website_schema( $org_id = '' ) {
	if ( '' === $org_id ) {
		$org_id = imidzh_schema_organization_id();
	}

	$contact = imidzh_schema_contact_details();

	return array(
		'@type'      => 'WebSite',
		'@id'        => trailingslashit( home_url( '/' ) ) . '#website',
		'url'        => home_url( '/' ),
		'name'       => $contact['name'],
		'inLanguage' => IMIDZH_SCHEMA_IN_LANGUAGE,
		'publisher'  => array(
			'@id' => $org_id,
		),
	);
}

/**
 * Fallback JSON-LD when Yoast is not active.
 *
 * If inc/search.php already prints WebSite (+ SearchAction), this outputs
 * EducationalOrganization only so WebSite is not duplicated.
 */
function imidzh_output_fallback_jsonld() {
	if ( imidzh_is_yoast_active() ) {
		return;
	}

	if ( is_admin() || is_feed() || ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) ) {
		return;
	}

	$org_id = imidzh_schema_organization_id();
	$graph  = array(
		imidzh_get_educational_organization_schema( $org_id ),
	);

	// Search module already prints WebSite + SearchAction on wp_head priority 20.
	if ( ! function_exists( 'imidzh_search_action_jsonld' ) && ! function_exists( 'imidzh_search_action_schema' ) ) {
		$graph[] = imidzh_get_website_schema( $org_id );
	}

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	$json = wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG );
	if ( ! is_string( $json ) || '' === $json ) {
		return;
	}

	echo '<script type="application/ld+json">' . $json . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'imidzh_output_fallback_jsonld', 20 );

/**
 * Yoast graph piece: EducationalOrganization with the same @id as Organization.
 *
 * Returns only @id and @type so Yoast can merge onto its Organization node.
 * Contact fields are filled later, and only where Yoast left them empty.
 */
if ( ! class_exists( 'Imidzh_Yoast_Educational_Organization_Piece', false ) ) {
	/**
	 * Schema piece generator for Yoast’s graph API.
	 */
	class Imidzh_Yoast_Educational_Organization_Piece {

		/**
		 * Yoast Meta_Tags_Context.
		 *
		 * @var object|null
		 */
		public $context;

		/**
		 * @param object $context Yoast schema context.
		 */
		public function __construct( $context ) {
			$this->context = $context;
		}

		/**
		 * Always attach institution identity to the graph.
		 *
		 * @return bool
		 */
		public function is_needed() {
			return true;
		}

		/**
		 * @return array<string, mixed>
		 */
		public function generate() {
			$site_url = ( is_object( $this->context ) && ! empty( $this->context->site_url ) )
				? (string) $this->context->site_url
				: '';

			return array(
				'@id'   => imidzh_schema_organization_id( $site_url ),
				'@type' => array( 'EducationalOrganization', 'HighSchool' ),
			);
		}
	}
}

/**
 * Register the EducationalOrganization piece on Yoast’s graph.
 *
 * @param array  $pieces  Schema piece generators.
 * @param object $context Yoast schema context.
 * @return array
 */
function imidzh_yoast_schema_graph_pieces( $pieces, $context ) {
	if ( ! is_array( $pieces ) ) {
		return $pieces;
	}

	$pieces[] = new Imidzh_Yoast_Educational_Organization_Piece( $context );
	return $pieces;
}
add_filter( 'wpseo_schema_graph_pieces', 'imidzh_yoast_schema_graph_pieces', 11, 2 );

/**
 * Upgrade Yoast’s Organization node in place (empty fields only).
 *
 * @param mixed $data Organization schema piece.
 * @return mixed
 */
function imidzh_yoast_schema_organization( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	return imidzh_merge_organization_schema( $data );
}
add_filter( 'wpseo_schema_organization', 'imidzh_yoast_schema_organization' );

/**
 * Final graph pass: one organization node, institution fields filled if empty.
 *
 * @param mixed  $graph   Schema graph.
 * @param object $context Yoast schema context.
 * @return mixed
 */
function imidzh_yoast_schema_graph( $graph, $context = null ) {
	if ( ! is_array( $graph ) ) {
		return $graph;
	}

	$site_url  = ( is_object( $context ) && ! empty( $context->site_url ) ) ? (string) $context->site_url : '';
	$org_id    = imidzh_schema_organization_id( $site_url );
	$first_org = null;
	$deduped   = array();

	foreach ( $graph as $piece ) {
		if ( ! is_array( $piece ) || ! imidzh_schema_piece_is_organization( $piece ) ) {
			$deduped[] = $piece;
			continue;
		}

		if ( null === $first_org ) {
			$piece = imidzh_merge_organization_schema( $piece );
			if ( empty( $piece['@id'] ) ) {
				$piece['@id'] = $org_id;
			}
			$first_org = count( $deduped );
			$deduped[] = $piece;
			continue;
		}

		$deduped[ $first_org ] = imidzh_merge_organization_schema( $deduped[ $first_org ], $piece );
	}

	if ( null === $first_org ) {
		$deduped[] = imidzh_get_educational_organization_schema( $org_id );
	}

	return $deduped;
}
add_filter( 'wpseo_schema_graph', 'imidzh_yoast_schema_graph', 11, 2 );

/**
 * Add inLanguage / publisher to Yoast WebSite without touching SearchAction.
 *
 * @param mixed $data WebSite schema piece.
 * @return mixed
 */
function imidzh_yoast_schema_website( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	if ( empty( $data['inLanguage'] ) ) {
		$data['inLanguage'] = IMIDZH_SCHEMA_IN_LANGUAGE;
	}

	if ( empty( $data['publisher'] ) ) {
		$data['publisher'] = array(
			'@id' => imidzh_schema_organization_id(),
		);
	}

	return $data;
}
add_filter( 'wpseo_schema_website', 'imidzh_yoast_schema_website' );

/**
 * Official front-page title (used only when Yoast’s SEO title is empty).
 *
 * @return string
 */
function imidzh_default_front_page_title() {
	return __( 'Ліцей «Імідж» — Ужгородської міської ради Закарпатської області', 'imidzh' );
}

/**
 * One-sentence front-page meta description.
 *
 * @return string
 */
function imidzh_default_front_page_metadesc() {
	return __( 'Ужгородський ліцей «Імідж» забезпечує сучасну освіту, безпечне середовище та відкриту інформацію для учнів і батьків.', 'imidzh' );
}

/**
 * Stored Yoast SEO title for the current front page, if any.
 *
 * @return string
 */
function imidzh_get_stored_yoast_front_title() {
	if ( is_front_page() && is_page() ) {
		$post_id = get_queried_object_id();
		if ( $post_id ) {
			$meta = get_post_meta( $post_id, '_yoast_wpseo_title', true );
			if ( ! imidzh_yoast_value_is_empty( $meta ) ) {
				return (string) $meta;
			}
		}
	}

	$titles = get_option( 'wpseo_titles', array() );
	if ( is_array( $titles ) && ! imidzh_yoast_value_is_empty( $titles['title-home-wpseo'] ?? '' ) ) {
		return (string) $titles['title-home-wpseo'];
	}

	return '';
}

/**
 * Stored Yoast meta description for the current view, if any.
 *
 * @param int $post_id Optional post ID.
 * @return string
 */
function imidzh_get_stored_yoast_metadesc( $post_id = 0 ) {
	if ( is_front_page() ) {
		if ( is_page() ) {
			$page_id = $post_id ? (int) $post_id : get_queried_object_id();
			if ( $page_id ) {
				$meta = get_post_meta( $page_id, '_yoast_wpseo_metadesc', true );
				if ( ! imidzh_yoast_value_is_empty( $meta ) ) {
					return (string) $meta;
				}
			}
		}

		$titles = get_option( 'wpseo_titles', array() );
		if ( is_array( $titles ) && ! imidzh_yoast_value_is_empty( $titles['metadesc-home-wpseo'] ?? '' ) ) {
			return (string) $titles['metadesc-home-wpseo'];
		}
	}

	$post_id = $post_id ? (int) $post_id : get_queried_object_id();
	if ( $post_id ) {
		$meta = get_post_meta( $post_id, '_yoast_wpseo_metadesc', true );
		if ( ! imidzh_yoast_value_is_empty( $meta ) ) {
			return (string) $meta;
		}
	}

	return '';
}

/**
 * Slugs of seeded IA pages (legal / official section pages).
 *
 * @return array<int, string>
 */
function imidzh_get_seeded_page_slugs() {
	$slugs = array( 'about', 'transparency', 'education', 'parents', 'safety', 'teachers', 'news', 'contacts' );

	if ( ! function_exists( 'imidzh_get_information_architecture' ) ) {
		return $slugs;
	}

	$walk = static function ( $items ) use ( &$walk, &$slugs ) {
		if ( ! is_array( $items ) ) {
			return;
		}
		foreach ( $items as $item ) {
			if ( ! empty( $item['slug'] ) && is_string( $item['slug'] ) ) {
				$slugs[] = $item['slug'];
			}
			if ( ! empty( $item['children'] ) && is_array( $item['children'] ) ) {
				$walk( $item['children'] );
			}
		}
	};

	$walk( imidzh_get_information_architecture() );

	return array_values( array_unique( $slugs ) );
}

/**
 * Whether the post is a seeded official / legal IA page.
 *
 * @param int|WP_Post|null $post Post ID or object.
 * @return bool
 */
function imidzh_is_seeded_legal_page( $post = null ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return false;
	}

	return in_array( $post->post_name, imidzh_get_seeded_page_slugs(), true );
}

/**
 * Front-page title default when Yoast leaves the SEO title empty.
 *
 * Does not overwrite a title saved in Yoast (metabox or Search Appearance)
 * or a title Yoast already generated from its templates.
 *
 * @param mixed $title Title from Yoast.
 * @return mixed
 */
function imidzh_yoast_front_page_title( $title ) {
	if ( ! is_front_page() ) {
		return $title;
	}

	if ( ! imidzh_yoast_value_is_empty( imidzh_get_stored_yoast_front_title() ) ) {
		return $title;
	}

	if ( ! imidzh_yoast_value_is_empty( $title ) ) {
		return $title;
	}

	return imidzh_default_front_page_title();
}
add_filter( 'wpseo_title', 'imidzh_yoast_front_page_title', 20 );

/**
 * Default meta descriptions when Yoast postmeta / templates are empty.
 *
 * @param mixed $metadesc Description from Yoast.
 * @return mixed
 */
function imidzh_yoast_default_metadesc( $metadesc ) {
	if ( ! imidzh_yoast_value_is_empty( $metadesc ) ) {
		return $metadesc;
	}

	if ( is_front_page() ) {
		if ( ! imidzh_yoast_value_is_empty( imidzh_get_stored_yoast_metadesc() ) ) {
			return $metadesc;
		}
		return imidzh_default_front_page_metadesc();
	}

	if ( ! is_singular( 'page' ) ) {
		return $metadesc;
	}

	$post_id = get_queried_object_id();
	if ( ! $post_id || ! imidzh_is_seeded_legal_page( $post_id ) ) {
		return $metadesc;
	}

	if ( ! imidzh_yoast_value_is_empty( imidzh_get_stored_yoast_metadesc( $post_id ) ) ) {
		return $metadesc;
	}

	$page_title = get_the_title( $post_id );
	if ( imidzh_yoast_value_is_empty( $page_title ) ) {
		return $metadesc;
	}

	return sprintf(
		/* translators: %s: page title */
		__( 'Офіційна інформація ліцею «Імідж»: %s.', 'imidzh' ),
		$page_title
	);
}
add_filter( 'wpseo_metadesc', 'imidzh_yoast_default_metadesc', 20 );

/**
 * Enable Yoast breadcrumb theme support (HTML is opt-in via imidzh_the_breadcrumbs()).
 */
function imidzh_yoast_theme_support() {
	add_theme_support( 'yoast-seo-breadcrumbs' );
}
add_action( 'after_setup_theme', 'imidzh_yoast_theme_support' );

/**
 * Print Yoast breadcrumbs when the plugin provides them.
 *
 * Templates may call this later; header.php / page.php are not modified here.
 */
function imidzh_the_breadcrumbs() {
	if ( ! function_exists( 'yoast_breadcrumb' ) ) {
		return;
	}

	yoast_breadcrumb(
		'<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Навігаційний ланцюжок', 'imidzh' ) . '">',
		'</nav>'
	);
}
