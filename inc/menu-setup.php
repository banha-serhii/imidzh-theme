<?php
/**
 * Information architecture, page seeding, menus, fallback, mega-menu CSS.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Canonical IA tree (English slugs). «Головна» is the brand link, not a nav item.
 *
 * Contacts lives under «Про ліцей» in the primary menu, but the page itself is
 * a root permalink: /contacts/ (not /about/contacts/).
 *
 * «Новини» is a Page with slug `news`. Recommend assigning it as the Posts page
 * in Settings → Reading. Do not change Reading settings on every request.
 *
 * @return array<int, array<string, mixed>>
 */
function imidzh_get_information_architecture() {
	return array(
		array(
			'title'    => __( 'Про ліцей', 'imidzh' ),
			'slug'     => 'about',
			'wide'     => true,
			'children' => array(
				array(
					'title' => __( 'Адміністрація', 'imidzh' ),
					'slug'  => 'administration',
				),
				array(
					'title' => __( 'Педагогічний колектив', 'imidzh' ),
					'slug'  => 'staff',
				),
				array(
					'title' => __( 'Рада ліцею', 'imidzh' ),
					'slug'  => 'council',
				),
				array(
					'title' => __( 'Матеріально-технічна база', 'imidzh' ),
					'slug'  => 'facilities',
				),
				array(
					'title' => __( 'Інклюзія та умови доступності', 'imidzh' ),
					'slug'  => 'accessibility',
				),
				array(
					'title' => __( 'Вакансії', 'imidzh' ),
					'slug'  => 'vacancies',
				),
				array(
					'title' => __( 'Контакти та розташування', 'imidzh' ),
					'slug'  => 'contacts',
					'root'  => true,
				),
			),
		),
		array(
			'title'    => __( 'Прозорість та звітність', 'imidzh' ),
			'slug'     => 'transparency',
			'wide'     => true,
			'children' => array(
				array(
					'title' => __( 'Статут закладу', 'imidzh' ),
					'slug'  => 'statute',
				),
				array(
					'title' => __( 'Ліцензія на провадження освітньої діяльності', 'imidzh' ),
					'slug'  => 'license',
				),
				array(
					'title' => __( 'Річний звіт керівника', 'imidzh' ),
					'slug'  => 'annual-report',
				),
				array(
					'title' => __( 'Забезпечення якості освіти', 'imidzh' ),
					'slug'  => 'quality-assurance',
				),
				array(
					'title' => __( 'Мережа та наповнюваність класів', 'imidzh' ),
					'slug'  => 'class-network',
				),
				array(
					'title' => __( 'Мова освітнього процесу', 'imidzh' ),
					'slug'  => 'language',
				),
				array(
					'title' => __( 'Правила внутрішнього розпорядку', 'imidzh' ),
					'slug'  => 'internal-regulations',
				),
				array(
					'title' => __( 'Фінансовий звіт та кошторис', 'imidzh' ),
					'slug'  => 'finance',
				),
				array(
					'title' => __( 'Штатний розпис', 'imidzh' ),
					'slug'  => 'staffing-table',
				),
				array(
					'title' => __( 'Договори та публічні закупівлі', 'imidzh' ),
					'slug'  => 'procurement',
				),
			),
		),
		array(
			'title'    => __( 'Освітній процес', 'imidzh' ),
			'slug'     => 'education',
			'wide'     => true,
			'children' => array(
				array(
					'title' => __( 'Структура навчального року', 'imidzh' ),
					'slug'  => 'academic-year',
				),
				array(
					'title' => __( 'Освітні програми та навчальні плани', 'imidzh' ),
					'slug'  => 'curriculum',
				),
				array(
					'title' => __( 'Розклади та графіки занять', 'imidzh' ),
					'slug'  => 'timetables',
				),
				array(
					'title' => __( 'Дистанційне навчання', 'imidzh' ),
					'slug'  => 'distance-learning',
				),
				array(
					'title' => __( 'Електронні підручники', 'imidzh' ),
					'slug'  => 'e-textbooks',
				),
				array(
					'title' => __( 'Олімпіади та конкурси', 'imidzh' ),
					'slug'  => 'olympiads',
				),
				array(
					'title' => __( 'Підсумкова атестація (НМТ / ДПА)', 'imidzh' ),
					'slug'  => 'assessment',
				),
				array(
					'title' => __( 'Учнівське самоврядування', 'imidzh' ),
					'slug'  => 'student-government',
				),
			),
		),
		array(
			'title'    => __( 'Вступникам та батькам', 'imidzh' ),
			'slug'     => 'parents',
			'wide'     => false,
			'children' => array(
				array(
					'title' => __( 'Правила прийому та територія обслуговування', 'imidzh' ),
					'slug'  => 'admission',
				),
				array(
					'title' => __( 'Графік особистого прийому громадян', 'imidzh' ),
					'slug'  => 'visiting-hours',
				),
				array(
					'title' => __( 'Організація харчування', 'imidzh' ),
					'slug'  => 'meals',
				),
				array(
					'title' => __( 'Правила поведінки учнів', 'imidzh' ),
					'slug'  => 'code-of-conduct',
				),
				array(
					'title' => __( 'Психологічна служба та логопед', 'imidzh' ),
					'slug'  => 'support-services',
				),
			),
		),
		array(
			'title'    => __( 'Безпека та захист', 'imidzh' ),
			'slug'     => 'safety',
			'wide'     => false,
			'children' => array(
				array(
					'title' => __( 'Алгоритм дій під час повітряної тривоги', 'imidzh' ),
					'slug'  => 'air-raid',
				),
				array(
					'title' => __( 'Протидія булінгу та омбудсман', 'imidzh' ),
					'slug'  => 'anti-bullying',
				),
				array(
					'title' => __( 'Запобігання домашньому насильству', 'imidzh' ),
					'slug'  => 'domestic-violence',
				),
				array(
					'title' => __( 'Безпечний інтернет', 'imidzh' ),
					'slug'  => 'safer-internet',
				),
				array(
					'title' => __( 'Охорона праці та цивільний захист', 'imidzh' ),
					'slug'  => 'civil-protection',
				),
			),
		),
		array(
			'title'    => __( 'Педагогам', 'imidzh' ),
			'slug'     => 'teachers',
			'wide'     => false,
			'children' => array(
				array(
					'title' => __( 'Підвищення кваліфікації', 'imidzh' ),
					'slug'  => 'professional-development',
				),
				array(
					'title' => __( 'Атестація педагогів', 'imidzh' ),
					'slug'  => 'attestation',
				),
				array(
					'title' => __( 'Замовлення та вибір підручників', 'imidzh' ),
					'slug'  => 'textbooks',
				),
			),
		),
		array(
			'title'    => __( 'Новини', 'imidzh' ),
			'slug'     => 'news',
			'wide'     => false,
			'children' => array(),
		),
	);
}

/**
 * Placeholder body for seeded pages.
 *
 * @param string $title Page title.
 * @return string
 */
function imidzh_ia_placeholder_content( $title ) {
	$intro = sprintf(
		/* translators: %s: page title */
		__( 'Ця сторінка розділу «%s» містить офіційну інформацію Ужгородського ліцею «Імідж».', 'imidzh' ),
		$title
	);

	return '<p>' . esc_html( $intro ) . '</p><p>' . esc_html__( 'Документи будуть додані.', 'imidzh' ) . '</p>';
}

/**
 * Find a page by slug, preferring the expected parent.
 *
 * @param string $slug      Post name.
 * @param int    $parent_id Expected parent page ID (0 = root).
 * @return int Page ID or 0.
 */
function imidzh_find_page_by_slug( $slug, $parent_id = 0 ) {
	$slug      = sanitize_title( $slug );
	$parent_id = (int) $parent_id;

	$query = array(
		'name'             => $slug,
		'post_type'        => 'page',
		'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
		'posts_per_page'   => 1,
		'suppress_filters' => true,
		'no_found_rows'    => true,
	);

	$with_parent              = $query;
	$with_parent['post_parent'] = $parent_id;
	$found                    = get_posts( $with_parent );
	if ( ! empty( $found ) ) {
		return (int) $found[0]->ID;
	}

	$found = get_posts( $query );
	if ( ! empty( $found ) ) {
		return (int) $found[0]->ID;
	}

	return 0;
}

/**
 * Get or create a published page. Match by slug; never duplicate.
 *
 * Existing content is left untouched. Parent and publish status are corrected.
 *
 * @param string $slug      Post name.
 * @param string $title     Page title (used only on create).
 * @param int    $parent_id Parent page ID.
 * @return int Page ID or 0.
 */
function imidzh_get_or_create_page( $slug, $title, $parent_id = 0 ) {
	$parent_id = (int) $parent_id;
	$page_id   = imidzh_find_page_by_slug( $slug, $parent_id );

	if ( $page_id ) {
		$update = array( 'ID' => $page_id );
		$page   = get_post( $page_id );

		if ( $page && (int) $page->post_parent !== $parent_id ) {
			$update['post_parent'] = $parent_id;
		}
		if ( $page && 'publish' !== $page->post_status ) {
			$update['post_status'] = 'publish';
		}
		if ( $page && $page->post_name !== sanitize_title( $slug ) ) {
			$update['post_name'] = sanitize_title( $slug );
		}

		if ( count( $update ) > 1 ) {
			wp_update_post( $update );
		}

		return $page_id;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => sanitize_title( $slug ),
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_parent'  => $parent_id,
			'post_content' => imidzh_ia_placeholder_content( $title ),
			'post_author'  => get_current_user_id() ? get_current_user_id() : 1,
		),
		true
	);

	if ( is_wp_error( $page_id ) ) {
		return 0;
	}

	return (int) $page_id;
}

/**
 * Seed all IA pages. Returns a map of path => page ID.
 *
 * @return array<string, int>
 */
function imidzh_seed_ia_pages() {
	$ids = array();

	foreach ( imidzh_get_information_architecture() as $section ) {
		$parent_id = imidzh_get_or_create_page( $section['slug'], $section['title'], 0 );
		if ( ! $parent_id ) {
			continue;
		}
		$ids[ $section['slug'] ] = $parent_id;

		foreach ( $section['children'] as $child ) {
			$child_parent = ! empty( $child['root'] ) ? 0 : $parent_id;
			$child_id     = imidzh_get_or_create_page( $child['slug'], $child['title'], $child_parent );
			if ( ! $child_id ) {
				continue;
			}
			$key           = ! empty( $child['root'] ) ? $child['slug'] : $section['slug'] . '/' . $child['slug'];
			$ids[ $key ]   = $child_id;
		}
	}

	return $ids;
}

/**
 * Get or create a named nav menu.
 *
 * @param string $name Menu name.
 * @return int Term ID or 0.
 */
function imidzh_get_or_create_nav_menu( $name ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu && ! is_wp_error( $menu ) ) {
		return (int) $menu->term_id;
	}

	$menu_id = wp_create_nav_menu( $name );
	if ( is_wp_error( $menu_id ) ) {
		return 0;
	}

	return (int) $menu_id;
}

/**
 * Remove all items from a nav menu (used only during explicit seed).
 *
 * @param int $menu_id Menu term ID.
 */
function imidzh_clear_nav_menu_items( $menu_id ) {
	$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	if ( empty( $items ) || is_wp_error( $items ) ) {
		return;
	}

	foreach ( $items as $item ) {
		wp_delete_post( (int) $item->ID, true );
	}
}

/**
 * Assign a menu to a theme location without wiping other locations.
 *
 * @param string $location Theme location slug.
 * @param int    $menu_id  Menu term ID.
 */
function imidzh_assign_nav_menu( $location, $menu_id ) {
	$locations = get_nav_menu_locations();
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}
	$locations[ $location ] = (int) $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Insert a page as a nav menu item.
 *
 * @param int    $menu_id        Menu term ID.
 * @param int    $page_id        Page ID.
 * @param int    $parent_item_id Parent menu item ID.
 * @param string $classes        Extra CSS classes.
 * @return int Menu item ID or 0.
 */
function imidzh_add_page_menu_item( $menu_id, $page_id, $parent_item_id = 0, $classes = '' ) {
	if ( ! $page_id ) {
		return 0;
	}

	$item_id = wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => get_the_title( $page_id ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => (int) $page_id,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => (int) $parent_item_id,
			'menu-item-classes'   => $classes,
		)
	);

	return is_wp_error( $item_id ) ? 0 : (int) $item_id;
}

/**
 * Build primary, footer, and footer_2 menus from seeded page IDs.
 *
 * Sidebar is skipped: page.php already uses it as a section nav and a generated
 * tree would fight that layout.
 *
 * @param array<string, int> $page_ids Path => page ID.
 */
function imidzh_seed_nav_menus( $page_ids ) {
	$primary_id = imidzh_get_or_create_nav_menu( __( 'Головне меню', 'imidzh' ) );
	if ( $primary_id ) {
		imidzh_clear_nav_menu_items( $primary_id );

		foreach ( imidzh_get_information_architecture() as $section ) {
			if ( empty( $page_ids[ $section['slug'] ] ) ) {
				continue;
			}

			$classes     = ! empty( $section['wide'] ) ? 'mega-menu--wide' : '';
			$parent_item = imidzh_add_page_menu_item( $primary_id, $page_ids[ $section['slug'] ], 0, $classes );

			foreach ( $section['children'] as $child ) {
				$key = ! empty( $child['root'] ) ? $child['slug'] : $section['slug'] . '/' . $child['slug'];
				if ( empty( $page_ids[ $key ] ) ) {
					continue;
				}
				imidzh_add_page_menu_item( $primary_id, $page_ids[ $key ], $parent_item );
			}
		}

		imidzh_assign_nav_menu( 'primary', $primary_id );
	}

	$footer_id = imidzh_get_or_create_nav_menu( __( 'Футер: Навігація', 'imidzh' ) );
	if ( $footer_id ) {
		imidzh_clear_nav_menu_items( $footer_id );
		$footer_keys = array( 'about', 'education', 'news', 'contacts' );
		foreach ( $footer_keys as $key ) {
			if ( ! empty( $page_ids[ $key ] ) ) {
				imidzh_add_page_menu_item( $footer_id, $page_ids[ $key ] );
			}
		}
		imidzh_assign_nav_menu( 'footer', $footer_id );
	}

	$footer_2_id = imidzh_get_or_create_nav_menu( __( 'Футер: Прозорість', 'imidzh' ) );
	if ( $footer_2_id ) {
		imidzh_clear_nav_menu_items( $footer_2_id );
		$legal_keys = array(
			'transparency/statute',
			'transparency/license',
			'transparency/annual-report',
			'transparency/finance',
			'transparency/staffing-table',
			'transparency/procurement',
		);
		foreach ( $legal_keys as $key ) {
			if ( ! empty( $page_ids[ $key ] ) ) {
				imidzh_add_page_menu_item( $footer_2_id, $page_ids[ $key ] );
			}
		}
		imidzh_assign_nav_menu( 'footer_2', $footer_2_id );
	}
}

/**
 * Idempotent IA seed: pages + menus. Safe to run more than once.
 *
 * @return bool
 */
function imidzh_seed_information_architecture() {
	static $running = false;
	if ( $running ) {
		return true;
	}
	$running = true;

	$page_ids = imidzh_seed_ia_pages();
	imidzh_seed_nav_menus( $page_ids );

	update_option( 'imidzh_ia_seeded', 1, false );
	flush_rewrite_rules( false );

	$running = false;

	return ! empty( $page_ids );
}

/**
 * Seed once when the theme is activated.
 */
function imidzh_ia_after_switch_theme() {
	if ( get_option( 'imidzh_ia_seeded' ) ) {
		return;
	}
	imidzh_seed_information_architecture();
}
add_action( 'after_switch_theme', 'imidzh_ia_after_switch_theme' );

/**
 * Auto-seed for administrators if the theme was already active when IA landed.
 * Never runs for anonymous front-end visitors.
 */
function imidzh_maybe_auto_seed_ia() {
	if ( get_option( 'imidzh_ia_seeded' ) ) {
		return;
	}
	if ( function_exists( 'wp_installing' ) && wp_installing() ) {
		return;
	}
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	imidzh_seed_information_architecture();
}
add_action( 'admin_init', 'imidzh_maybe_auto_seed_ia', 20 );
add_action( 'wp', 'imidzh_maybe_auto_seed_ia', 5 );

/**
 * Admin: Appearance → Інформаційна архітектура.
 */
function imidzh_ia_admin_menu() {
	add_theme_page(
		__( 'Інформаційна архітектура', 'imidzh' ),
		__( 'Інформаційна архітектура', 'imidzh' ),
		'edit_theme_options',
		'imidzh-ia',
		'imidzh_ia_admin_page'
	);
}
add_action( 'admin_menu', 'imidzh_ia_admin_menu' );

/**
 * Handle seed form POST.
 */
function imidzh_ia_handle_admin_seed() {
	if ( ! isset( $_POST['imidzh_seed_ia'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	check_admin_referer( 'imidzh_seed_ia' );

	imidzh_seed_information_architecture();

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'            => 'imidzh-ia',
				'imidzh-ia-done'  => '1',
			),
			admin_url( 'themes.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'imidzh_ia_handle_admin_seed' );

/**
 * Notice until IA has been seeded.
 */
function imidzh_ia_admin_notice() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	if ( get_option( 'imidzh_ia_seeded' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && isset( $screen->id ) && 'appearance_page_imidzh-ia' === $screen->id ) {
		return;
	}

	$url = admin_url( 'themes.php?page=imidzh-ia' );
	echo '<div class="notice notice-warning"><p>';
	echo esc_html__( 'Тему «Імідж» активовано, але сторінки та меню інформаційної архітектури ще не створено.', 'imidzh' );
	echo ' <a href="' . esc_url( $url ) . '">' . esc_html__( 'Створити сторінки та меню', 'imidzh' ) . '</a>';
	echo '</p></div>';
}
add_action( 'admin_notices', 'imidzh_ia_admin_notice' );

/**
 * Admin page: seed / re-seed.
 */
function imidzh_ia_admin_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$seeded = (bool) get_option( 'imidzh_ia_seeded' );
	$done   = isset( $_GET['imidzh-ia-done'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Інформаційна архітектура', 'imidzh' ); ?></h1>

		<?php if ( $done ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Сторінки та меню оновлено. Існуючий вміст сторінок не змінювався.', 'imidzh' ); ?></p></div>
		<?php endif; ?>

		<p><?php esc_html_e( 'Створює канонічне дерево сторінок (англійські slug) і призначає меню «Головне меню», футер-навігацію та посилання прозорості. Повторний запуск не дублює сторінки (пошук за slug). Пункти меню буде зібрано заново.', 'imidzh' ); ?></p>
		<p><?php esc_html_e( 'Рекомендація: Налаштування → Читання → «Сторінка записів» призначте сторінці «Новини» (/news/). Тема не змінює ці опції з коду.', 'imidzh' ); ?></p>
		<p>
			<?php
			echo $seeded
				? esc_html__( 'Статус: архітектуру вже було створено.', 'imidzh' )
				: esc_html__( 'Статус: архітектуру ще не створювали.', 'imidzh' );
			?>
		</p>

		<form method="post" action="">
			<?php wp_nonce_field( 'imidzh_seed_ia' ); ?>
			<?php
			submit_button(
				$seeded
					? __( 'Оновити сторінки та меню', 'imidzh' )
					: __( 'Створити сторінки та меню', 'imidzh' ),
				'primary',
				'imidzh_seed_ia'
			);
			?>
		</form>
	</div>
	<?php
}

/**
 * Canonical permalink for an IA node.
 *
 * @param string               $section_slug Parent slug.
 * @param array<string, mixed> $child        Optional child node.
 * @return string
 */
function imidzh_ia_node_url( $section_slug, $child = null ) {
	if ( null === $child ) {
		return home_url( '/' . $section_slug . '/' );
	}
	if ( ! empty( $child['root'] ) ) {
		return home_url( '/' . $child['slug'] . '/' );
	}
	return home_url( '/' . $section_slug . '/' . $child['slug'] . '/' );
}

/**
 * Fallback primary menu when no menu is assigned.
 *
 * Two-level markup matches the walker (button trigger + panel) so hover/keyboard
 * work before Appearance → Menus is saved. No «Головна».
 */
if ( ! function_exists( 'imidzh_fallback_menu' ) ) {
	function imidzh_fallback_menu() {
		echo '<ul id="mega-menu" class="mega-menu">';

		foreach ( imidzh_get_information_architecture() as $section ) {
			$children = isset( $section['children'] ) ? $section['children'] : array();
			$parent_url = imidzh_ia_node_url( $section['slug'] );

			if ( empty( $children ) ) {
				printf(
					'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
					esc_url( $parent_url ),
					esc_html( $section['title'] )
				);
				continue;
			}

			$slug      = sanitize_html_class( $section['slug'] );
			$panel_id  = 'mega-panel-' . $slug;
			$li_class  = 'menu-item menu-item-has-children';
			$panel_cls = 'mega-menu__panel mega-menu__panel--simple';
			if ( ! empty( $section['wide'] ) ) {
				$li_class  .= ' mega-menu--wide';
				$panel_cls .= ' mega-menu__panel--wide';
			}

			printf( '<li class="%s">', esc_attr( $li_class ) );
			printf(
				'<button type="button" class="mega-menu__trigger" aria-expanded="false" aria-haspopup="true" aria-controls="%1$s" id="mega-trigger-%2$s"><span>%3$s</span><span class="mega-menu__chevron" aria-hidden="true"></span></button>',
				esc_attr( $panel_id ),
				esc_attr( $slug ),
				esc_html( $section['title'] )
			);
			printf(
				'<div id="%1$s" class="%2$s" role="region"><ul class="mega-menu__list" role="list">',
				esc_attr( $panel_id ),
				esc_attr( $panel_cls )
			);

			foreach ( $children as $child ) {
				printf(
					'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
					esc_url( imidzh_ia_node_url( $section['slug'], $child ) ),
					esc_html( $child['title'] )
				);
			}

			echo '</ul></div></li>';
		}

		echo '</ul>';
	}
}

/**
 * Enqueue mega-menu / nav-density stylesheet after the main theme CSS.
 */
function imidzh_enqueue_mega_menu_assets() {
	wp_enqueue_style(
		'imidzh-mega-menu',
		IMIDZH_URI . '/assets/css/mega-menu.css',
		array( 'imidzh-style' ),
		IMIDZH_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'imidzh_enqueue_mega_menu_assets', 20 );
