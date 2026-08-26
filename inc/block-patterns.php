<?php
/**
 * Block pattern categories and editor/front assets for team cards.
 *
 * Theme pattern PHP files in `patterns/` are auto-registered by WordPress 6.0+.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register pattern categories used by theme pattern files.
 *
 * Core registers `featured`. `team` exists from WP 6.2; register it on older installs.
 */
function imidzh_register_block_pattern_categories() {
	$registry = WP_Block_Pattern_Categories_Registry::get_instance();

	if ( ! $registry->is_registered( 'team' ) ) {
		register_block_pattern_category(
			'team',
			array(
				'label' => __( 'Команда', 'imidzh' ),
			)
		);
	}

	if ( ! $registry->is_registered( 'imidzh-hubs' ) ) {
		register_block_pattern_category(
			'imidzh-hubs',
			array(
				'label' => __( 'Хаби розділів', 'imidzh' ),
			)
		);
	}
}
add_action( 'init', 'imidzh_register_block_pattern_categories' );

/**
 * Enqueue pattern styles on the front end and in the editor canvas.
 */
function imidzh_enqueue_pattern_assets() {
	wp_enqueue_style(
		'imidzh-admin-cards',
		IMIDZH_URI . '/assets/css/admin-cards.css',
		array(),
		IMIDZH_VERSION
	);

	wp_enqueue_style(
		'imidzh-patterns',
		IMIDZH_URI . '/assets/css/patterns.css',
		array(),
		IMIDZH_VERSION
	);
}
add_action( 'enqueue_block_assets', 'imidzh_enqueue_pattern_assets' );
