<?php
/**
 * Hub pages: template assignment, starter content, child grid.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

/**
 * Starter content version. Bump when hub pattern markup changes.
 */
define( 'IMIDZH_HUBS_CONTENT_VERSION', 1 );

/**
 * Page template file for section hubs.
 */
define( 'IMIDZH_HUB_TEMPLATE', 'page-hub.php' );

/**
 * Canonical hub slugs (root pages). News is an archive, not a hub.
 *
 * @return string[]
 */
function imidzh_get_hub_slugs() {
	return array(
		'about',
		'transparency',
		'education',
		'parents',
		'safety',
		'teachers',
		'contacts',
	);
}

/**
 * Short card blurbs by child slug (used when the page has no manual excerpt).
 *
 * @return array<string, string>
 */
function imidzh_get_hub_card_blurbs() {
	return array(
		'administration'           => __( 'Керівництво закладу та контакти адміністрації', 'imidzh' ),
		'staff'                    => __( 'Педагогічний колектив за кафедрами та предметами', 'imidzh' ),
		'council'                  => __( 'Громадське самоврядування ліцею', 'imidzh' ),
		'facilities'               => __( 'Приміщення, кабінети та матеріальна база', 'imidzh' ),
		'accessibility'            => __( 'Інклюзія та умови доступності', 'imidzh' ),
		'vacancies'                => __( 'Вільні посади педагогічних працівників', 'imidzh' ),
		'contacts'                 => __( 'Адреса, телефон, електронна пошта, розташування', 'imidzh' ),
		'statute'                  => __( 'Основний установчий документ закладу', 'imidzh' ),
		'license'                  => __( 'Право на провадження освітньої діяльності', 'imidzh' ),
		'annual-report'            => __( 'Звіт директора за навчальний рік', 'imidzh' ),
		'quality-assurance'        => __( 'Внутрішня система забезпечення якості освіти', 'imidzh' ),
		'class-network'            => __( 'Класи, паралелі та наповнюваність', 'imidzh' ),
		'language'                 => __( 'Мова навчання та спілкування в ліцеї', 'imidzh' ),
		'internal-regulations'     => __( 'Правила для працівників і учнів', 'imidzh' ),
		'finance'                  => __( 'Кошторис та використання коштів', 'imidzh' ),
		'staffing-table'           => __( 'Штат працівників закладу', 'imidzh' ),
		'procurement'              => __( 'Публічні закупівлі та договори', 'imidzh' ),
		'academic-year'            => __( 'Семестри, канікули та календар року', 'imidzh' ),
		'curriculum'               => __( 'Освітні програми та навчальні плани', 'imidzh' ),
		'timetables'               => __( 'Уроки, дзвінки та графіки занять', 'imidzh' ),
		'distance-learning'        => __( 'Організація навчання в дистанційному режимі', 'imidzh' ),
		'e-textbooks'              => __( 'Доступ до електронних підручників', 'imidzh' ),
		'olympiads'                => __( 'Олімпіади, конкурси та турніри', 'imidzh' ),
		'assessment'               => __( 'НМТ, ДПА та підготовка випускників', 'imidzh' ),
		'student-government'       => __( 'Учнівська рада та учнівські ініціативи', 'imidzh' ),
		'admission'                => __( 'Правила вступу та територія обслуговування', 'imidzh' ),
		'visiting-hours'           => __( 'Коли керівництво приймає відвідувачів', 'imidzh' ),
		'meals'                    => __( 'Організація харчування учнів', 'imidzh' ),
		'code-of-conduct'          => __( 'Правила поведінки в ліцеї', 'imidzh' ),
		'support-services'         => __( 'Психолог і логопед', 'imidzh' ),
		'air-raid'                 => __( 'Що робити, коли лунає повітряна тривога', 'imidzh' ),
		'anti-bullying'            => __( 'Протидія цькуванню та захист прав дитини', 'imidzh' ),
		'domestic-violence'        => __( 'Допомога у випадках домашнього насильства', 'imidzh' ),
		'safer-internet'           => __( 'Безпека дітей у цифровому середовищі', 'imidzh' ),
		'civil-protection'         => __( 'Охорона праці та цивільний захист', 'imidzh' ),
		'professional-development' => __( 'Курси та підвищення кваліфікації', 'imidzh' ),
		'attestation'              => __( 'Порядок атестації педагогічних працівників', 'imidzh' ),
		'textbooks'                => __( 'Замовлення та вибір підручників', 'imidzh' ),
	);
}

/**
 * Normalize post content for placeholder / starter comparison.
 *
 * @param string $content Raw post_content.
 * @return string
 */
function imidzh_hub_normalize_content( $content ) {
	$content = str_replace( "\r\n", "\n", (string) $content );
	return trim( $content );
}

/**
 * Extract block markup from a theme pattern file (content after the PHP header).
 *
 * @param string $hub_slug Hub slug, e.g. safety.
 * @return string
 */
function imidzh_get_hub_pattern_markup( $hub_slug ) {
	$hub_slug = sanitize_file_name( $hub_slug );
	if ( ! in_array( $hub_slug, imidzh_get_hub_slugs(), true ) ) {
		return '';
	}

	$file = IMIDZH_DIR . '/patterns/hub-' . $hub_slug . '.php';
	if ( ! is_readable( $file ) ) {
		return '';
	}

	ob_start();
	include $file;
	return imidzh_hub_normalize_content( (string) ob_get_clean() );
}

/**
 * Whether page body is empty or still the IA seed placeholder.
 *
 * @param WP_Post $post Page.
 * @return bool
 */
function imidzh_hub_is_placeholder_content( $post ) {
	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	$content = imidzh_hub_normalize_content( $post->post_content );
	if ( '' === $content ) {
		return true;
	}

	if ( false !== strpos( $content, '<?php' ) ) {
		return true;
	}

	if ( ! function_exists( 'imidzh_ia_placeholder_content' ) ) {
		return false;
	}

	$placeholder = imidzh_hub_normalize_content( imidzh_ia_placeholder_content( $post->post_title ) );
	if ( $content === $placeholder ) {
		return true;
	}

	$plain    = imidzh_hub_normalize_content( wp_strip_all_tags( $content ) );
	$ph_plain = imidzh_hub_normalize_content( wp_strip_all_tags( $placeholder ) );
	if ( $plain === $ph_plain ) {
		return true;
	}

	$marker = __( 'Документи будуть додані.', 'imidzh' );
	return ( false !== strpos( $plain, $marker ) && false !== strpos( $plain, 'Ця сторінка розділу' ) && strlen( $plain ) < 400 );
}

/**
 * Whether hub page content may be replaced by the starter pattern.
 *
 * Customized editor content is never overwritten.
 *
 * @param WP_Post $post             Page.
 * @param bool    $replace_starters Also replace previously applied (unmodified) starters.
 * @return bool
 */
function imidzh_hub_content_is_replaceable( $post, $replace_starters = false ) {
	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	if ( imidzh_hub_is_placeholder_content( $post ) ) {
		return true;
	}

	$content = imidzh_hub_normalize_content( $post->post_content );
	$starter = imidzh_get_hub_pattern_markup( $post->post_name );

	if ( $starter && $content === $starter ) {
		return false;
	}

	if ( ! $replace_starters ) {
		return false;
	}

	$stored = (string) get_post_meta( $post->ID, '_imidzh_hub_starter_hash', true );
	if ( '' !== $stored && hash_equals( $stored, md5( $content ) ) ) {
		return true;
	}

	return false;
}

/**
 * Assign the hub template to a page if needed.
 *
 * @param int $page_id Page ID.
 * @return bool True when the template was written.
 */
function imidzh_hub_assign_template( $page_id ) {
	$page_id = (int) $page_id;
	if ( $page_id <= 0 ) {
		return false;
	}

	$current = get_post_meta( $page_id, '_wp_page_template', true );
	if ( IMIDZH_HUB_TEMPLATE === $current ) {
		return false;
	}

	update_post_meta( $page_id, '_wp_page_template', IMIDZH_HUB_TEMPLATE );
	return true;
}

/**
 * Write starter pattern markup onto a hub page.
 *
 * @param WP_Post $post    Page.
 * @param string  $markup  Pattern HTML.
 * @return bool
 */
function imidzh_hub_write_starter_content( $post, $markup ) {
	if ( ! $post instanceof WP_Post || '' === $markup ) {
		return false;
	}

	$result = wp_update_post(
		array(
			'ID'           => (int) $post->ID,
			'post_content' => $markup,
		),
		true
	);

	if ( is_wp_error( $result ) || ! $result ) {
		return false;
	}

	$saved = get_post( $post->ID );
	$hash  = $saved instanceof WP_Post
		? md5( imidzh_hub_normalize_content( $saved->post_content ) )
		: md5( imidzh_hub_normalize_content( $markup ) );

	update_post_meta( $post->ID, '_imidzh_hub_starter_hash', $hash );
	update_post_meta( $post->ID, '_imidzh_hub_content_version', IMIDZH_HUBS_CONTENT_VERSION );

	return true;
}

/**
 * Apply hub template (always) and starter content (only if replaceable).
 *
 * @param array $args {
 *     @type bool $replace_starters Replace unmodified previously applied starters.
 * }
 * @return array{templates:int,content:int,skipped:int,missing:int}
 */
function imidzh_apply_hub_pages( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'replace_starters' => false,
		)
	);

	$stats = array(
		'templates' => 0,
		'content'   => 0,
		'skipped'   => 0,
		'missing'   => 0,
	);

	if ( ! function_exists( 'imidzh_find_page_by_slug' ) ) {
		return $stats;
	}

	foreach ( imidzh_get_hub_slugs() as $slug ) {
		$page_id = imidzh_find_page_by_slug( $slug, 0 );
		if ( ! $page_id ) {
			++$stats['missing'];
			continue;
		}

		if ( imidzh_hub_assign_template( $page_id ) ) {
			++$stats['templates'];
		}

		$post = get_post( $page_id );
		if ( ! $post instanceof WP_Post ) {
			++$stats['missing'];
			continue;
		}

		$markup = imidzh_get_hub_pattern_markup( $slug );
		if ( '' === $markup ) {
			++$stats['skipped'];
			continue;
		}

		if ( ! imidzh_hub_content_is_replaceable( $post, (bool) $args['replace_starters'] ) ) {
			++$stats['skipped'];
			continue;
		}

		if ( imidzh_hub_write_starter_content( $post, $markup ) ) {
			++$stats['content'];
		} else {
			++$stats['skipped'];
		}
	}

	update_option( 'imidzh_hubs_content_version', IMIDZH_HUBS_CONTENT_VERSION, false );
	set_theme_mod( 'imidzh_hubs_seeded', 1 );

	return $stats;
}

/**
 * IA section node by slug.
 *
 * @param string $slug Section slug.
 * @return array<string, mixed>|null
 */
function imidzh_get_ia_section_by_slug( $slug ) {
	if ( ! function_exists( 'imidzh_get_information_architecture' ) ) {
		return null;
	}

	$slug = sanitize_title( $slug );
	foreach ( imidzh_get_information_architecture() as $section ) {
		if ( isset( $section['slug'] ) && $section['slug'] === $slug ) {
			return $section;
		}
	}

	return null;
}

/**
 * Published child pages for a hub, in IA order.
 *
 * About includes /contacts/ even though that page is a root permalink.
 *
 * @param int|WP_Post|null $post Hub page.
 * @return WP_Post[]
 */
function imidzh_get_hub_child_pages( $post = null ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post ) {
		return array();
	}

	$section = imidzh_get_ia_section_by_slug( $post->post_name );
	$pages   = array();
	$seen    = array();

	if ( $section && ! empty( $section['children'] ) && is_array( $section['children'] ) ) {
		foreach ( $section['children'] as $child ) {
			if ( empty( $child['slug'] ) ) {
				continue;
			}
			$child_parent = ! empty( $child['root'] ) ? 0 : (int) $post->ID;
			$child_id     = function_exists( 'imidzh_find_page_by_slug' )
				? imidzh_find_page_by_slug( $child['slug'], $child_parent )
				: 0;
			if ( ! $child_id ) {
				continue;
			}
			$child_post = get_post( $child_id );
			if ( ! $child_post instanceof WP_Post || 'publish' !== $child_post->post_status ) {
				continue;
			}
			$pages[]            = $child_post;
			$seen[ $child_id ]  = true;
		}
	} else {
		$children = get_pages(
			array(
				'parent'      => (int) $post->ID,
				'post_status' => 'publish',
				'sort_column' => 'menu_order,post_title',
			)
		);
		if ( is_array( $children ) ) {
			foreach ( $children as $child_post ) {
				$pages[]                 = $child_post;
				$seen[ (int) $child_post->ID ] = true;
			}
		}
	}

	if ( 'about' === $post->post_name ) {
		$contacts_id = function_exists( 'imidzh_find_page_by_slug' ) ? imidzh_find_page_by_slug( 'contacts', 0 ) : 0;
		if ( $contacts_id && empty( $seen[ $contacts_id ] ) ) {
			$contacts = get_post( $contacts_id );
			if ( $contacts instanceof WP_Post && 'publish' === $contacts->post_status ) {
				$pages[] = $contacts;
			}
		}
	}

	return $pages;
}

/**
 * Excerpt / blurb for a hub card.
 *
 * @param WP_Post $page Child page.
 * @return string
 */
function imidzh_get_hub_card_text( $page ) {
	if ( ! $page instanceof WP_Post ) {
		return '';
	}

	if ( has_excerpt( $page ) ) {
		return wp_strip_all_tags( get_the_excerpt( $page ) );
	}

	$blurbs = imidzh_get_hub_card_blurbs();
	if ( isset( $blurbs[ $page->post_name ] ) ) {
		return $blurbs[ $page->post_name ];
	}

	return '';
}

/**
 * Render the live child-page grid for the current hub.
 *
 * @param int|WP_Post|null $post Hub page.
 */
function imidzh_the_hub_children_grid( $post = null ) {
	$post  = get_post( $post );
	$pages = imidzh_get_hub_child_pages( $post );
	if ( empty( $pages ) ) {
		return;
	}

	$heading_id = 'hub-grid-heading';
	?>
	<nav class="hub-grid" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
		<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="hub-grid__title">
			<?php esc_html_e( 'У цьому розділі', 'imidzh' ); ?>
		</h2>
		<ul class="hub-grid__list">
			<?php foreach ( $pages as $child ) : ?>
				<?php
				$url        = get_permalink( $child );
				$excerpt    = imidzh_get_hub_card_text( $child );
				$card_class = 'hub-card';
				if ( 'air-raid' === $child->post_name ) {
					$card_class .= ' hub-card--priority';
				}
				?>
				<li class="<?php echo esc_attr( $card_class ); ?>">
					<a class="hub-card__link" href="<?php echo esc_url( $url ); ?>">
						<h3 class="hub-card__title"><?php echo esc_html( get_the_title( $child ) ); ?></h3>
						<?php if ( '' !== $excerpt ) : ?>
							<p class="hub-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
						<?php endif; ?>
						<span class="hub-card__more" aria-hidden="true"><?php esc_html_e( 'Перейти', 'imidzh' ); ?> &rarr;</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Optional map on the contacts hub (Customizer embed URL).
 *
 * @param int|WP_Post|null $post Hub page.
 */
function imidzh_the_hub_contacts_map( $post = null ) {
	$post = get_post( $post );
	if ( ! $post instanceof WP_Post || 'contacts' !== $post->post_name ) {
		return;
	}
	if ( ! function_exists( 'imidzh_sanitize_map_embed_url' ) ) {
		return;
	}

	$map_url = imidzh_sanitize_map_embed_url( get_theme_mod( 'imidzh_map_embed_url', '' ) );
	if ( '' === $map_url ) {
		return;
	}
	?>
	<div class="hub-contacts-map">
		<iframe
			src="<?php echo esc_url( $map_url ); ?>"
			title="<?php esc_attr_e( 'Мапа розташування ліцею', 'imidzh' ); ?>"
			loading="lazy"
			referrerpolicy="no-referrer-when-downgrade"
			allowfullscreen
		></iframe>
	</div>
	<?php
}

/**
 * Front-end hub stylesheet (editor already gets it via enqueue_block_assets).
 */
function imidzh_enqueue_hub_front_assets() {
	if ( ! is_page_template( IMIDZH_HUB_TEMPLATE ) ) {
		return;
	}

	if ( wp_style_is( 'imidzh-hub', 'enqueued' ) ) {
		return;
	}

	$deps = array( 'imidzh-style' );
	if ( wp_style_is( 'imidzh-patterns', 'registered' ) || wp_style_is( 'imidzh-patterns', 'enqueued' ) ) {
		$deps[] = 'imidzh-patterns';
	}

	wp_enqueue_style(
		'imidzh-hub',
		IMIDZH_URI . '/assets/css/hub.css',
		$deps,
		IMIDZH_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'imidzh_enqueue_hub_front_assets', 25 );

/**
 * Mark hub pages for CSS hooks.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function imidzh_hub_body_class( $classes ) {
	if ( is_page_template( IMIDZH_HUB_TEMPLATE ) ) {
		$classes[] = 'imidzh-hub';
	}
	return $classes;
}
add_filter( 'body_class', 'imidzh_hub_body_class' );

/**
 * After theme switch: fill hubs that still have the seed placeholder.
 */
function imidzh_hubs_after_switch_theme() {
	imidzh_apply_hub_pages(
		array(
			'replace_starters' => false,
		)
	);
}
add_action( 'after_switch_theme', 'imidzh_hubs_after_switch_theme', 30 );

/**
 * One-time apply for existing installs (placeholder hubs only).
 */
function imidzh_maybe_auto_apply_hub_pages() {
	if ( function_exists( 'wp_installing' ) && wp_installing() ) {
		return;
	}
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	if ( ! get_option( 'imidzh_ia_seeded' ) ) {
		return;
	}
	if ( (int) get_option( 'imidzh_hubs_content_version', 0 ) >= IMIDZH_HUBS_CONTENT_VERSION ) {
		return;
	}

	imidzh_apply_hub_pages(
		array(
			'replace_starters' => false,
		)
	);
}
add_action( 'admin_init', 'imidzh_maybe_auto_apply_hub_pages', 25 );

/**
 * Handle «Оновити контент хабів» from the IA admin screen.
 */
function imidzh_hubs_handle_admin_apply() {
	if ( ! isset( $_POST['imidzh_apply_hubs'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	check_admin_referer( 'imidzh_apply_hubs' );

	$stats = imidzh_apply_hub_pages(
		array(
			'replace_starters' => true,
		)
	);

	wp_safe_redirect(
		add_query_arg(
			array(
				'page'                 => 'imidzh-ia',
				'imidzh-hubs-done'     => '1',
				'imidzh-hubs-content'  => (int) $stats['content'],
				'imidzh-hubs-skipped'  => (int) $stats['skipped'],
				'imidzh-hubs-template' => (int) $stats['templates'],
			),
			admin_url( 'themes.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'imidzh_hubs_handle_admin_apply' );

/**
 * Success notice after a manual hub apply.
 */
function imidzh_hubs_admin_success_notice() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	if ( empty( $_GET['imidzh-hubs-done'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'appearance_page_imidzh-ia' !== $screen->id ) {
		return;
	}

	$content  = isset( $_GET['imidzh-hubs-content'] ) ? absint( $_GET['imidzh-hubs-content'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$skipped  = isset( $_GET['imidzh-hubs-skipped'] ) ? absint( $_GET['imidzh-hubs-skipped'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$template = isset( $_GET['imidzh-hubs-template'] ) ? absint( $_GET['imidzh-hubs-template'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	echo '<div class="notice notice-success is-dismissible"><p>';
	echo esc_html(
		sprintf(
			/* translators: 1: pages filled, 2: skipped (customized), 3: templates assigned */
			__( 'Контент хабів оновлено. Заповнено сторінок: %1$d. Пропущено (уже відредаговані або актуальні): %2$d. Призначено шаблон: %3$d.', 'imidzh' ),
			$content,
			$skipped,
			$template
		)
	);
	echo '</p></div>';
}
add_action( 'admin_notices', 'imidzh_hubs_admin_success_notice' );

/**
 * Markup for the hub-apply panel on the IA admin page.
 */
function imidzh_render_hubs_admin_panel() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$version = (int) get_option( 'imidzh_hubs_content_version', 0 );
	?>
	<hr>
	<h2><?php esc_html_e( 'Хаби розділів', 'imidzh' ); ?></h2>
	<p><?php esc_html_e( 'Призначає шаблон «Хаб розділу» батьківським сторінкам і заповнює порожній або плейсхолдерний вміст стартовим блоковим текстом. Сторінки, які вже редагували в редакторі, не змінюються.', 'imidzh' ); ?></p>
	<p>
		<?php
		echo $version
			? esc_html(
				sprintf(
					/* translators: %d: content version number */
					__( 'Поточна версія контенту хабів: %d.', 'imidzh' ),
					$version
				)
			)
			: esc_html__( 'Контент хабів ще не застосовували.', 'imidzh' );
		?>
	</p>
	<form method="post" action="">
		<?php wp_nonce_field( 'imidzh_apply_hubs' ); ?>
		<?php
		submit_button(
			__( 'Оновити контент хабів', 'imidzh' ),
			'secondary',
			'imidzh_apply_hubs',
			false
		);
		?>
	</form>
	<?php
}
add_action( 'imidzh_ia_admin_page_after', 'imidzh_render_hubs_admin_panel' );
