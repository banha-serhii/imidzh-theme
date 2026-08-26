<?php
/**
 * Front page: Customizer media/contacts + section data helpers.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize Google Maps / OSM embed URL.
 *
 * @param string $url Raw URL.
 * @return string
 */
function imidzh_sanitize_map_embed_url( $url ) {
	$url = esc_url_raw( trim( (string) $url ) );
	if ( '' === $url ) {
		return '';
	}

	$host = wp_parse_url( $url, PHP_URL_HOST );
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );

	if ( ! is_string( $host ) || '' === $host ) {
		return '';
	}

	$host = strtolower( $host );
	$ok   = (
		false !== strpos( $host, 'google.' )
		|| false !== strpos( $host, 'googleusercontent.com' )
		|| false !== strpos( $host, 'openstreetmap.org' )
		|| false !== strpos( $host, 'maps.apple.com' )
	);

	if ( ! $ok ) {
		return '';
	}

	// Prefer embed endpoints for Google.
	if ( false !== strpos( $host, 'google.' ) && false === strpos( $path, '/maps/embed' ) && false === strpos( $url, 'output=embed' ) ) {
		// Still allow — browser may redirect; admin should paste embed src.
		return $url;
	}

	return $url;
}

/**
 * Attachment image HTML helper.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Image size.
 * @param array  $attr          img attributes.
 * @return string
 */
function imidzh_get_attachment_image( $attachment_id, $size = 'large', $attr = array() ) {
	$attachment_id = absint( $attachment_id );
	if ( ! $attachment_id ) {
		return '';
	}
	$html = wp_get_attachment_image( $attachment_id, $size, false, $attr );
	return is_string( $html ) ? $html : '';
}

/**
 * Homepage Customizer section + controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function imidzh_homepage_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'imidzh_homepage',
		array(
			'title'       => __( 'Головна сторінка', 'imidzh' ),
			'description' => __( 'Фото, оголошення, графік роботи та мапа для демо головної.', 'imidzh' ),
			'priority'    => 32,
		)
	);

	$wp_customize->add_setting(
		'imidzh_home_hero_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'imidzh_home_hero_image',
			array(
				'label'     => __( 'Фон Hero (fallback без слайдера)', 'imidzh' ),
				'section'   => 'imidzh_homepage',
				'mime_type' => 'image',
				'priority'  => 10,
			)
		)
	);

	$wp_customize->add_setting(
		'imidzh_hero_notice_title',
		array(
			'default'           => __( 'Важливе оголошення', 'imidzh' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'imidzh_hero_notice_title',
		array(
			'label'    => __( 'Заголовок оголошення в Hero', 'imidzh' ),
			'section'  => 'imidzh_homepage',
			'type'     => 'text',
			'priority' => 20,
		)
	);

	$wp_customize->add_setting(
		'imidzh_hero_notice_text',
		array(
			'default'           => __( 'Триває прийом документів для вступу до 1-х та 10-х класів на новий навчальний рік.', 'imidzh' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'imidzh_hero_notice_text',
		array(
			'label'    => __( 'Текст оголошення', 'imidzh' ),
			'section'  => 'imidzh_homepage',
			'type'     => 'textarea',
			'priority' => 21,
		)
	);

	$wp_customize->add_setting(
		'imidzh_hero_notice_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'imidzh_hero_notice_link',
		array(
			'label'       => __( 'Посилання оголошення', 'imidzh' ),
			'description' => __( 'Порожньо = /parents/admission/', 'imidzh' ),
			'section'     => 'imidzh_homepage',
			'type'        => 'url',
			'priority'    => 22,
		)
	);

	$wp_customize->add_setting(
		'imidzh_hero_notice_link_label',
		array(
			'default'           => __( 'Графік прийому та перелік документів', 'imidzh' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'imidzh_hero_notice_link_label',
		array(
			'label'    => __( 'Текст кнопки оголошення', 'imidzh' ),
			'section'  => 'imidzh_homepage',
			'type'     => 'text',
			'priority' => 23,
		)
	);

	foreach ( array( 1, 2, 3 ) as $i ) {
		$setting = 'imidzh_home_gallery_' . $i;
		$wp_customize->add_setting(
			$setting,
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				$setting,
				array(
					/* translators: %d: image slot number */
					'label'     => sprintf( __( 'Галерея «Життя ліцею» — фото %d', 'imidzh' ), $i ),
					'section'   => 'imidzh_homepage',
					'mime_type' => 'image',
					'priority'  => 30 + $i,
				)
			)
		);
	}

	$wp_customize->add_setting(
		'imidzh_hours',
		array(
			'default'           => __( 'Пн–Пт: 8:00–17:00', 'imidzh' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'imidzh_hours',
		array(
			'label'    => __( 'Графік роботи', 'imidzh' ),
			'section'  => 'imidzh_homepage',
			'type'     => 'text',
			'priority' => 40,
		)
	);

	$wp_customize->add_setting(
		'imidzh_map_embed_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'imidzh_sanitize_map_embed_url',
		)
	);
	$wp_customize->add_control(
		'imidzh_map_embed_url',
		array(
			'label'       => __( 'URL вбудованої мапи', 'imidzh' ),
			'description' => __( 'У Google Maps: Поділитися → Вбудовування карти → скопіюйте src з iframe (посилання з /maps/embed).', 'imidzh' ),
			'section'     => 'imidzh_homepage',
			'type'        => 'url',
			'priority'    => 41,
		)
	);

	$wp_customize->add_setting(
		'imidzh_home_contact_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'imidzh_home_contact_image',
			array(
				'label'       => __( 'Фото біля контактів (якщо немає мапи)', 'imidzh' ),
				'description' => __( 'Показується, коли URL мапи порожній.', 'imidzh' ),
				'section'     => 'imidzh_homepage',
				'mime_type'   => 'image',
				'priority'    => 42,
			)
		)
	);
}
add_action( 'customize_register', 'imidzh_homepage_customize_register' );

/**
 * Quick-access cards for the front page.
 *
 * @return array<int, array{title:string,text:string,url:string,slug:string}>
 */
function imidzh_get_home_quick_links() {
	$items = array(
		array(
			'slug'  => 'news',
			'title' => __( 'Новини', 'imidzh' ),
			'text'  => __( 'Оголошення та події ліцею', 'imidzh' ),
			'path'  => 'news',
		),
		array(
			'slug'  => 'parents',
			'title' => __( 'Вступникам', 'imidzh' ),
			'text'  => __( 'Правила прийому та документи для батьків', 'imidzh' ),
			'path'  => 'parents',
		),
		array(
			'slug'  => 'transparency',
			'title' => __( 'Прозорість', 'imidzh' ),
			'text'  => __( 'Документи за ст. 30 ЗУ «Про освіту»', 'imidzh' ),
			'path'  => 'transparency',
		),
		array(
			'slug'  => 'education',
			'title' => __( 'Освітній процес', 'imidzh' ),
			'text'  => __( 'Плани, розклади, дистанційне навчання', 'imidzh' ),
			'path'  => 'education',
		),
		array(
			'slug'  => 'safety',
			'title' => __( 'Безпека', 'imidzh' ),
			'text'  => __( 'Тривога, булінг, цивільний захист', 'imidzh' ),
			'path'  => 'safety',
		),
		array(
			'slug'  => 'contacts',
			'title' => __( 'Контакти', 'imidzh' ),
			'text'  => __( 'Адреса, телефон, розташування', 'imidzh' ),
			'path'  => 'contacts',
		),
	);

	$out = array();
	foreach ( $items as $item ) {
		$page = get_page_by_path( $item['path'] );
		$url  = ( $page instanceof WP_Post && 'publish' === $page->post_status )
			? get_permalink( $page )
			: home_url( '/' . trim( $item['path'], '/' ) . '/' );

		$out[] = array(
			'slug'  => $item['slug'],
			'title' => $item['title'],
			'text'  => $item['text'],
			'url'   => $url,
		);
	}

	return $out;
}

/**
 * Transparency highlights for the front page.
 *
 * @return array<int, array{title:string,url:string}>
 */
function imidzh_get_home_transparency_links() {
	$items = array(
		array(
			'title' => __( 'Ліцензія', 'imidzh' ),
			'path'  => 'transparency/license',
		),
		array(
			'title' => __( 'Статут', 'imidzh' ),
			'path'  => 'transparency/statute',
		),
		array(
			'title' => __( 'Фінанси та кошторис', 'imidzh' ),
			'path'  => 'transparency/finance',
		),
		array(
			'title' => __( 'Закупівлі', 'imidzh' ),
			'path'  => 'transparency/procurement',
		),
	);

	$out = array();
	foreach ( $items as $item ) {
		$page = get_page_by_path( $item['path'] );
		$url  = ( $page instanceof WP_Post && 'publish' === $page->post_status )
			? get_permalink( $page )
			: home_url( '/' . trim( $item['path'], '/' ) . '/' );

		$out[] = array(
			'title' => $item['title'],
			'url'   => $url,
		);
	}

	return $out;
}

/**
 * Feature cards with deep links.
 *
 * @return array<int, array{title:string,text:string,url:string}>
 */
function imidzh_get_home_features() {
	$map = array(
		array(
			'title' => __( 'Високий рівень освіти', 'imidzh' ),
			'text'  => __( 'Кваліфікований педагогічний колектив та сучасні методики навчання.', 'imidzh' ),
			'path'  => 'about/staff',
		),
		array(
			'title' => __( 'Безпечне середовище', 'imidzh' ),
			'text'  => __( 'Облаштоване сучасне укриття, охорона та алгоритми дій під час тривоги.', 'imidzh' ),
			'path'  => 'safety',
		),
		array(
			'title' => __( 'Цифрові технології', 'imidzh' ),
			'text'  => __( 'Інтерактивні дошки, мультимедіа та сучасний комп\'ютерний клас.', 'imidzh' ),
			'path'  => 'about/facilities',
		),
		array(
			'title' => __( 'Інклюзивний простір', 'imidzh' ),
			'text'  => __( 'Безбар\'єрне середовище та підтримка кожного учня.', 'imidzh' ),
			'path'  => 'about/accessibility',
		),
	);

	$out = array();
	foreach ( $map as $i => $item ) {
		$page = get_page_by_path( $item['path'] );
		$url  = ( $page instanceof WP_Post && 'publish' === $page->post_status )
			? get_permalink( $page )
			: home_url( '/' . trim( $item['path'], '/' ) . '/' );

		$out[] = array(
			'icon'  => sprintf( '%02d', $i + 1 ),
			'title' => $item['title'],
			'text'  => $item['text'],
			'url'   => $url,
		);
	}

	return $out;
}

/**
 * Gallery attachment IDs from Customizer.
 *
 * @return int[]
 */
function imidzh_get_home_gallery_ids() {
	$ids = array();
	foreach ( array( 1, 2, 3 ) as $i ) {
		$id = absint( get_theme_mod( 'imidzh_home_gallery_' . $i, 0 ) );
		if ( $id ) {
			$ids[] = $id;
		}
	}
	return $ids;
}

/**
 * Resolve notice link for hero card.
 *
 * @return string
 */
function imidzh_get_hero_notice_link() {
	$custom = esc_url( get_theme_mod( 'imidzh_hero_notice_link', '' ) );
	if ( $custom ) {
		return $custom;
	}
	$page = get_page_by_path( 'parents/admission' );
	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}
	return home_url( '/parents/admission/' );
}
