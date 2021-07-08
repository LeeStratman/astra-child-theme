<?php
/**
 * Astra Child custom image functions and definitions.
 *
 * @package Astra Child
 * @since 1.0.0
 */

namespace SWD\Astra_Child\Images;

use SWD\Astra_Child\Component_Interface;
use function add_action;
use function add_filter;
use function is_category;

/**
 * Class for managing responsive image sizes.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'images';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_image_sizes' ), 20 );
		add_filter( 'image_size_names_choose', array( $this, 'filter_image_sizes' ) );
	}

	/**
	 * Adds custom image sizes.
	 */
	public function action_add_image_sizes() {

		/**
		 * Single featured image (full-width).
		 */
		add_image_size( 'astra-child-featured', 1920, 600, true );

		/**
		 * Showcase Archive. Used for services and projects archive.
		 */
		add_image_size( 'astra-child-showcase-archive', 900, 600, array( 'center', 'center' ) );

		/**
		 * Blog archive.
		 */
		add_image_size( 'astra-child-blog-archive', 409, 327, true );

		/**
		 * Blog archive.
		 */
		add_image_size( 'astra-child-blog-archive-wide', 960, 327, true );

		/**
		 * Team Member archive block.
		 */
		add_image_size( 'astra-child-team-member-archive', 218, 328, true );

		/**
		 * Single Team Member.
		 */
		add_image_size( 'astra-child-team-member', 408, 612, true );
	}

	/**
	 * Merge custom image sizes with registered image sizes.
	 *
	 * @param array $sizes An array of image sizes.
	 */
	public function filter_image_sizes( $sizes ) {

		return array_merge(
			$sizes,
			array()
		);
	}
}
