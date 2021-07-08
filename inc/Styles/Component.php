<?php
/**
 * SWD\Astra_Child\Styles\Component class
 *
 * @package Astra Child
 */

namespace SWD\Astra_Child\Styles;

use SWD\Astra_Child\Component_Interface;
use function SWD\Astra_Child\astra_child;
use function add_action;
use function wp_enqueue_style;
use function get_stylesheet_directory_uri;

/**
 * Class for managing stylesheets.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'styles';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_styles' ), 15 );
		add_action( 'init', array( $this, 'action_register_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'action_add_editor_styles' ) );
	}

	/**
	 * Registers or enqueues stylesheets.
	 */
	public function action_enqueue_styles() {
		wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/assets/css/style.css', array( 'astra-theme-css' ), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	}

	/**
	 * Enqueues WordPress theme styles for the editor.
	 */
	public function action_add_editor_styles() {

		$filters_js_path = '/assets/js/filters.editor.min.js';

		// Make sure to load block editor libraries first.
		$js_dependencies = array(
			'wp-rich-text',
			'astra-child-block-editor-js',
		);

		// Enqueue our block filters.
		wp_enqueue_script(
			'astra-child-filters-js',
			get_stylesheet_directory_uri() . $filters_js_path,
			$js_dependencies,
			filemtime( get_stylesheet_directory() . $filters_js_path ),
			true
		);

		/**
		 * Register custom block editor JavaScript.
		 */
		wp_enqueue_script(
			'astra-child-content-width-editor-js',
			get_stylesheet_directory_uri() . '/assets/js/editor.width.js',
			array(),
			filemtime( get_stylesheet_directory() . '/assets/js/editor.width.js' ),
			true
		);

		// Enqueue block editor stylesheet.
		wp_enqueue_style( 'astra-child-theme-editor-css', get_stylesheet_directory_uri() . '/assets/css/style-editor.css', false, CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	}

	/**
	 * Register editor assets.
	 */
	public function action_register_assets() {

		$blocks_js_path = '/assets/js/blocks.editor.min.js';

		// Make sure to load block editor libraries first.
		$js_dependencies = array(
			'wp-plugins',
			'wp-element',
			'wp-edit-post',
			'wp-i18n',
			'wp-api-request',
			'wp-data',
			'wp-hooks',
			'wp-plugins',
			'wp-components',
			'wp-blocks',
			'wp-editor',
			'wp-compose',
		);

		/**
		 * Register custom block editor JavaScript.
		 */
		wp_register_script(
			'astra-child-block-editor-js',
			get_stylesheet_directory_uri() . $blocks_js_path,
			$js_dependencies,
			filemtime( get_stylesheet_directory() . $blocks_js_path ),
			true
		);
	}
}
