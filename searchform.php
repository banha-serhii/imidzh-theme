<?php
/**
 * Search form — header (collapsed), drawer, and results page.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

$args = isset( $args ) && is_array( $args ) ? $args : array();

$context = isset( $args['imidzh_context'] ) ? sanitize_key( $args['imidzh_context'] ) : 'default';
if ( ! in_array( $context, array( 'header', 'drawer', 'default' ), true ) ) {
	$context = 'default';
}

$uid      = wp_unique_id( 'imidzh-search-' );
$field_id = $uid . '-field';
$aria     = ! empty( $args['aria_label'] ) ? $args['aria_label'] : __( 'Пошук по сайту', 'imidzh' );
$is_header = ( 'header' === $context );
?>
<div class="site-search site-search--<?php echo esc_attr( $context ); ?>">
	<?php if ( $is_header ) : ?>
		<button
			type="button"
			class="site-search__toggle"
			aria-expanded="false"
			aria-controls="<?php echo esc_attr( $field_id ); ?>"
		>
			<span class="screen-reader-text"><?php esc_html_e( 'Відкрити пошук', 'imidzh' ); ?></span>
			<svg class="site-search__icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
				<circle cx="11" cy="11" r="7"></circle>
				<path d="M20 20l-3.5-3.5"></path>
			</svg>
		</button>
	<?php endif; ?>
	<form
		role="search"
		method="get"
		class="search-form"
		action="<?php echo esc_url( home_url( '/' ) ); ?>"
		aria-label="<?php echo esc_attr( $aria ); ?>"
		<?php echo $is_header ? 'hidden' : ''; ?>
	>
		<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $aria ); ?></label>
		<input
			type="search"
			id="<?php echo esc_attr( $field_id ); ?>"
			class="search-form__field"
			name="s"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			placeholder="<?php esc_attr_e( 'Пошук…', 'imidzh' ); ?>"
			enterkeyhint="search"
		>
		<button type="submit" class="search-form__submit">
			<?php esc_html_e( 'Пошук', 'imidzh' ); ?>
		</button>
	</form>
</div>
