<?php
/**
 * Astra Child block patterns functions and definitions.
 *
 * @package Astra Child
 * @since 1.0.0
 */

namespace SWD\Astra_Child\Block_Patterns;

use SWD\Astra_Child\Component_Interface;
use function add_action;
use function register_block_pattern;
use function unregister_block_pattern;

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
		return 'patterns';
	}

	/**
	 * Class constructor.
	 */
	public function initialize() {
		add_action( 'init', array( $this, 'action_register_block_patterns' ) );
	}

	/**
	 * Register custom WordPress block patterns.
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-patterns/
	 */
	public function action_register_block_patterns() {

		$should_register_core_patterns = get_theme_support( 'core-block-patterns' );

		if ( $should_register_core_patterns ) {
			// Remove default patterns.
			unregister_block_pattern( 'core/text-two-columns' );
			unregister_block_pattern( 'core/two-buttons' );
			unregister_block_pattern( 'core/two-images' );
			unregister_block_pattern( 'core/text-two-columns-with-images' );
			unregister_block_pattern( 'core/text-three-columns-buttons' );
			unregister_block_pattern( 'core/large-header' );
			unregister_block_pattern( 'core/large-header-button' );
			unregister_block_pattern( 'core/three-buttons' );
			unregister_block_pattern( 'core/heading-paragraph' );
			unregister_block_pattern( 'core/quote' );

			// Basic Patterns.
			register_block_pattern( 'astra-child/basic', $this->load_block_pattern( 'basic' ) );
			register_block_pattern( 'astra-child/project-details', $this->load_block_pattern( 'project-details' ) );
		}

		register_block_pattern_category( 'basic', array( 'label' => _x( 'Basic', 'Block pattern category', 'astra-child' ) ) );
	}

	/**
	 * Load a block pattern by name.
	 *
	 * @param string $name Block Pattern File name.
	 *
	 * @return array Block Pattern Array.
	 */
	public function load_block_pattern( $name ) {
		return require __DIR__ . '/patterns/' . $name . '.php';
	}
}
