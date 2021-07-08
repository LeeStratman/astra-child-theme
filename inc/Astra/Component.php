<?php
/**
 * Astra Child Astra functions and definitions.
 *
 * @package Astra Child
 * @since 1.0.0
 */

namespace SWD\Astra_Child\Astra;

use SWD\Astra_Child\Component_Interface;
use function add_action;
use function add_filter;

/**
 * Class for integrating with the block editor.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'astra';
	}

	/**
	 * Class constructor.
	 */
	public function initialize() {
		add_filter( 'astra_mobile_breakpoint', array( $this, 'filter_theme_mobile_breakpoint' ) );
		add_filter( 'astra_tablet_breakpoint', array( $this, 'filter_theme_tablet_breakpoint' ) );
		add_filter( 'body_class', array( $this, 'filter_astra_body_classes' ) );
		add_filter( 'astra_post_thumbnail_default_size', array( $this, 'blog_default_thumbnail_size' ) );
		add_filter( 'astra_get_post_format', array( $this, 'taxonomy_template' ), 2, 10 );
		add_filter( 'astra_the_title_enabled', array( $this, 'remove_post_title' ) );
		add_action( 'loop_start', array( $this, 'astra_header_after' ) );
		add_filter( 'get_the_archive_title', array( $this, 'archive_title' ) );
		add_action( 'astra_single_post_title_after', array( $this, 'add_taxonomy_to_custom_post_types_title' ) );
	}

	/**
	 * Set mobile breakpoint in pixels;
	 */
	public function filter_theme_mobile_breakpoint() {
		return 768;
	}

	/**
	 * Set tablet breakpoint in pixels;
	 *
	 * For existing users, we recommend to
	 * please use the same breakpoint value for “astra_tablet_breakpoint”
	 * and from the Primary Menu > Menu breakpoint. So when your menu
	 * displays a Hamburger Menu it will load the tablet settings for both
	 *  ( i.e. customizer settings & CSS styling ).
	 */
	public function filter_theme_tablet_breakpoint() {
		return 1000;
	}

	/**
	 * Display blog title.
	 *
	 * TODO: Make an option to change.
	 */
	public function astra_header_after() {
		if ( is_home() ) {
			echo '<section class="ast-archive-description">
						<h1 class="page-title ast-archive-title">Blog</span></h1>
						</section>';
		}
	}

	/**
	 * Override taxonomy template.
	 *
	 * @param string $post_format          The post format.
	 * @param string $post_format_override Override post formate.
	 */
	public function taxonomy_template( $post_format, $post_format_override ) {

		if ( '' !== $post_format_override ) {
			$post_format = $post_format_override;
		}

		if ( is_tax() ) {
			return 'showcase';
		}

		return $post_format;
	}

	/**
	 * Removes Asta's default post title.
	 *
	 * @param bool $enabled Whether to show title or not.
	 */
	public function remove_post_title( $enabled ) {

		if ( is_front_page() ) {
			return false;
		}

		return $enabled;
	}

	/**
	 * Adds classes to body tag.
	 *
	 * @param array $classes An array of current classes.
	 */
	public function filter_astra_body_classes( $classes ) {

		/**
		 * Taxonomy Archive.
		 */
		if ( is_tax() ) {
			$classes[] = 'astra-child__full-view';
			$classes[] = 'astra-child__showcase-archive';
			$classes[] = 'astra-child__no-top-space';
			$classes[] = 'astra-child__no-bottom-space';
		}

		/**
		 * Home Page.
		 */
		if ( is_front_page() ) {
			$classes[] = 'astra-child__no-top-space';
			$classes[] = 'astra-child__no-bottom-space';
		}

		if ( is_singular() ) {
			$post_type = get_post_type();
			if ( 'astra_child_team' != $post_type ) {

				if ( has_post_thumbnail() ) {
					$classes[] = 'astra-child__no-top-space';
				}
			}
		}

		/**
		 * Projects Page.
		 */
		if ( is_home() ) {
			$classes[] = 'astra-child__wide-view';
		}

		return $classes;
	}

	/**
	 * For blog posts. Set archive blog thumbnail size.
	 *
	 * @param string $size The size of the image.
	 */
	public function blog_default_thumbnail_size( $size ) {

		if ( is_home() && ! is_front_page() ) {
			$size = 'astra-child-blog-archive-wide';
		} else {
			$size = 'astra-child-featured';
		}
		return $size;
	}

	/**
	 * Filters the taxonomy title.
	 *
	 * @param string $title The title of the archive.
	 */
	public function archive_title( $title ) {

		if ( is_tax() ) {
			$title = single_term_title( '', false );
		};
		return $title;
	}

	/**
	 * Adds custom taxonomies to custom post types titles.
	 */
	public function add_taxonomy_to_custom_post_types_title() {
		$current_post_type = get_post_type();
		$current_post_id   = get_the_ID();

		if ( 'astra_child_services' === $current_post_type ) {
			$args = array(
				'taxonomy'   => 'astra_child_service_type',
				'object_ids' => $current_post_id,
			);
		}

		if ( 'astra_child_projects' === $current_post_type ) {
			$args = array(
				'taxonomy'   => 'astra_child_project_type',
				'object_ids' => $current_post_id,
			);
		}

		$separator  = ',';
		$terms      = get_terms( $args );
		$loop_count = 1;
		$output_str = '';

		$output_str .= '<div class="entry-meta">';

		foreach ( $terms as $term ) {
			$term_link = get_term_link( $term->term_id );
			$output_str .= ( 1 != $loop_count && '' != $term->name ) ? $separator . ' ' : '';
			$output_str .= '<a href="' . $term_link . '">' . $term->name . '</a>';
			$loop_count ++;
		}

		$output_str .= '</div>';

		echo $output_str; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
