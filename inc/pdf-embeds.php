<?php
/**
 * Inline PDF file blocks: document-reader layout without a tiny nested widget.
 *
 * Gutenberg File blocks with “Show inline embed” render a browser PDF viewer
 * inside a short <object> (default 600px). That inner viewer has its own
 * scrollbar, so the page feels like it has two scroll areas.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether post content includes an inline PDF preview.
 *
 * @param WP_Post|int|null $post Post object, ID, or null for the current post.
 * @return bool
 */
function imidzh_post_has_pdf_embed( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return false;
	}

	$content = (string) $post->post_content;
	if ( '' === $content ) {
		return false;
	}

	if ( false !== strpos( $content, 'wp-block-file__embed' ) ) {
		return true;
	}

	if ( false !== stripos( $content, 'application/pdf' ) ) {
		return true;
	}

	return (bool) preg_match( '/\.pdf(?:\?|#|"|\'|$)/i', $content );
}

/**
 * Mark document pages so CSS can tighten chrome around the reader.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function imidzh_pdf_body_class( $classes ) {
	if ( is_singular() && imidzh_post_has_pdf_embed() ) {
		$classes[] = 'imidzh-has-pdf';
	}
	return $classes;
}
add_filter( 'body_class', 'imidzh_pdf_body_class' );

/**
 * Enqueue reader styles only where a PDF embed is present.
 */
function imidzh_enqueue_pdf_embed_assets() {
	if ( ! is_singular() || ! imidzh_post_has_pdf_embed() ) {
		return;
	}

	wp_enqueue_style(
		'imidzh-pdf-embeds',
		IMIDZH_URI . '/assets/css/pdf-embeds.css',
		array( 'imidzh-style' ),
		IMIDZH_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'imidzh_enqueue_pdf_embed_assets', 30 );

/**
 * Whether a File block is an inline PDF preview.
 *
 * @param array  $block         Parsed block.
 * @param string $block_content Rendered HTML.
 * @return bool
 */
function imidzh_is_pdf_file_block( $block, $block_content ) {
	$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
	$href  = isset( $attrs['href'] ) ? (string) $attrs['href'] : '';

	if ( ! empty( $attrs['displayPreview'] ) ) {
		if ( $href && preg_match( '/\.pdf(?:\?|#|$)/i', $href ) ) {
			return true;
		}
	}

	return false !== strpos( $block_content, 'wp-block-file__embed' )
		&& ( false !== stripos( $block_content, 'application/pdf' ) || preg_match( '/\.pdf/i', $block_content ) );
}

/**
 * Restyle core/file PDF previews as a viewport-height document reader.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string
 */
function imidzh_render_pdf_file_block( $block_content, $block ) {
	if ( ! imidzh_is_pdf_file_block( $block, $block_content ) ) {
		return $block_content;
	}

	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag() ) {
		$classes = (string) $processor->get_attribute( 'class' );
		if ( false === strpos( $classes, 'imidzh-pdf' ) ) {
			$processor->set_attribute( 'class', trim( $classes . ' imidzh-pdf' ) );
		}
	}

	if ( $processor->next_tag( 'OBJECT' ) ) {
		$style = (string) $processor->get_attribute( 'style' );
		$style = preg_replace( '/height\s*:\s*[^;]+;?/i', '', $style );
		$style = trim( (string) $style, "; \t\n\r\0\x0B" );
		if ( '' === $style ) {
			$processor->remove_attribute( 'style' );
		} else {
			$processor->set_attribute( 'style', $style );
		}

		$data = $processor->get_attribute( 'data' );
		if ( is_string( $data ) && '' !== $data && false === strpos( $data, '#' ) ) {
			$processor->set_attribute( 'data', esc_url( $data ) . '#view=FitH' );
		}

		$obj_class = (string) $processor->get_attribute( 'class' );
		if ( false === strpos( $obj_class, 'imidzh-pdf__embed' ) ) {
			$processor->set_attribute( 'class', trim( $obj_class . ' imidzh-pdf__embed' ) );
		}
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/file', 'imidzh_render_pdf_file_block', 10, 2 );
