<?php
/**
 * Astra Child Editor functions and definitions.
 *
 * @package Astra Child
 * @since 1.0.0
 */

namespace SWD\Astra_Child\Editor;

use SWD\Astra_Child\Component_Interface;
use function add_action;
use function add_theme_support;

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
		return 'editor';
	}

	/**
	 * Class constructor.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_support' ) );
	}

	/**
	 * Adds support for various editor features.
	 */
	public function action_add_editor_support() {
		/**
		 * Add support for color palettes.
		 *
		 * To preserve color behavior across themes, use these naming conventions:
		 * - Use primary and secondary color for main variations.
		 * - Use `theme-[color-name]` naming standard for standard colors (red, blue, etc).
		 * - Use `custom-[color-name]` for non-standard colors.
		 *
		 * Add the line below to disable the custom color picker in the editor.
		 * add_theme_support( 'disable-custom-colors' );
		 */
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Primary', 'astra-child' ),
					'slug'  => 'theme-primary',
					'color' => '#F3BD50',
				),
				array(
					'name'  => __( 'Black', 'astra-child' ),
					'slug'  => 'theme-black',
					'color' => '#26242B',
				),
				array(
					'name'  => __( 'Grey', 'astra-child' ),
					'slug'  => 'theme-grey',
					'color' => '#8A8A8D',
				),
				array(
					'name'  => __( 'White', 'astra-child' ),
					'slug'  => 'theme-white',
					'color' => '#ffffff',
				),
			)
		);

		/**
		 * Add support for custom font sizes.
		 */
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => __( 'Small', 'astra-child' ),
					'shortName' => __( 'S', 'astra-child' ),
					'size'      => 16,
					'slug'      => 'small',
				),
				array(
					'name'      => __( 'Medium', 'astra-child' ),
					'shortName' => __( 'M', 'astra-child' ),
					'size'      => 18,
					'slug'      => 'medium',
				),
				array(
					'name'      => __( 'Large', 'astra-child' ),
					'shortName' => __( 'L', 'astra-child' ),
					'size'      => 28,
					'slug'      => 'large',
				),
			)
		);
	}
}
