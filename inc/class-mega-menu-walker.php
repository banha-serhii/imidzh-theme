<?php
/**
 * Accessible Mega Menu Walker for Ліцей «Імідж».
 *
 * Appearance → Menus structure:
 * Level 0 — top bar items
 * Level 1 — dropdown links OR mega column titles (if parent has class `mega-menu--columns`)
 * Level 2 — links inside a mega column
 *
 * Add CSS class `mega-menu--columns` (or `mega`) on a top-level item to enable multi-column panel.
 *
 * @package Imidzh
 */

defined( 'ABSPATH' ) || exit;

class Imidzh_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Current top-level item uses multi-column mega layout.
	 *
	 * @var bool
	 */
	private $is_mega = false;

	/**
	 * Panel id for the open top-level parent.
	 *
	 * @var string
	 */
	private $panel_id = '';

	/**
	 * @param string   $output Used to append additional content.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( 0 === $depth ) {
			$panel_class = $this->is_mega
				? 'mega-menu__panel mega-menu__panel--mega'
				: 'mega-menu__panel mega-menu__panel--simple';

			$id_attr = $this->panel_id ? ' id="' . esc_attr( $this->panel_id ) . '"' : '';

			$output .= "\n{$indent}<div{$id_attr} class=\"{$panel_class}\" role=\"region\">\n";

			if ( $this->is_mega ) {
				$output .= "{$indent}\t<ul class=\"mega-menu__columns\" role=\"list\">\n";
			} else {
				$output .= "{$indent}\t<ul class=\"mega-menu__list\" role=\"list\">\n";
			}
			return;
		}

		// Nested list under a mega column title.
		$output .= "\n{$indent}<ul class=\"mega-menu__list\" role=\"list\">\n";
	}

	/**
	 * @param string   $output Used to append additional content.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( 0 === $depth ) {
			$output .= "{$indent}\t</ul>\n";
			$output .= "{$indent}</div><!-- .mega-menu__panel -->\n";
			$this->is_mega  = false;
			$this->panel_id = '';
			return;
		}

		$output .= "{$indent}</ul>\n";
	}

	/**
	 * @param string   $output Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   Menu arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( 0 === $depth ) {
			$this->is_mega = in_array( 'mega-menu--columns', $classes, true ) || in_array( 'mega', $classes, true );
			if ( $this->is_mega ) {
				$classes[] = 'mega-menu--columns';
			}
			$this->panel_id = $has_children ? 'mega-panel-' . $item->ID : '';
		}

		if ( 1 === $depth && $this->is_mega ) {
			$classes[] = 'mega-menu__column';
		}

		$class_names = implode( ' ', array_map( 'sanitize_html_class', array_filter( $classes ) ) );
		$output     .= '<li class="' . esc_attr( $class_names ) . '">';

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		// Top-level parent: accessible disclosure button.
		if ( 0 === $depth && $has_children ) {
			$output .= '<button type="button" class="mega-menu__trigger"';
			$output .= ' aria-expanded="false"';
			$output .= ' aria-haspopup="true"';
			$output .= ' aria-controls="' . esc_attr( $this->panel_id ) . '"';
			$output .= ' id="mega-trigger-' . esc_attr( (string) $item->ID ) . '">';
			$output .= '<span>' . esc_html( $title ) . '</span>';
			$output .= '<span class="mega-menu__chevron" aria-hidden="true"></span>';
			$output .= '</button>';
			return;
		}

		// Mega column title.
		$link_class = '';
		if ( 1 === $depth && $this->is_mega ) {
			$link_class = 'mega-menu__column-title';
		}

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( '' !== $value && null !== $value ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		if ( $link_class ) {
			$attributes .= ' class="' . esc_attr( $link_class ) . '"';
		}

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . esc_html( $title ) . ( isset( $args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * @param string   $output Used to append additional content.
	 * @param WP_Post  $item   Page data object.
	 * @param int      $depth  Depth of page.
	 * @param stdClass $args   Menu arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}
