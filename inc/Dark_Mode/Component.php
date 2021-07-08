<?php
/**
 * SWD\Astra_Child\Styles\Component class
 *
 * @package Astra Child
 */

namespace SWD\Astra_Child\Dark_Mode;

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
	 * Whether this component is enabled.
	 *
	 * @var $enabled.
	 */
	public $enabled = true;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'dark-mode';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'astra_logo', array( $this, 'dark_mode_logo' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_styles' ), 15 );
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
	}

	/**
	 * Get options and load values.
	 */
	public function load_values() {
		$options = get_option( 'astra_child_options' );

		$this->enabled = isset( $options['dark_mode_enabled'] ) ? true : false;
	}

	/**
	 * Registers or enqueues stylesheets.
	 */
	public function action_enqueue_styles() {
		$js_path = '/assets/js/darkmode.js';

		$options = get_option( 'astra_child_options' );

		if ( ! isset( $options['dark_mode_enabled'] ) ) {
			return;
		}

		// Enqueue our block filters.
		wp_enqueue_script(
			'astra-child-filters-js',
			get_stylesheet_directory_uri() . $js_path,
			array(),
			filemtime( get_stylesheet_directory() . $js_path ),
			true
		);
	}

	/**
	 * Display SVG as logo.
	 *
	 * @param string $html The current HTML.
	 */
	public function dark_mode_logo( $html ) {

		$options = get_option( 'astra_child_options' );

		if ( ! isset( $options['dark_mode_enabled'] ) || ! isset( $options['dark_mode_logo_url'] ) ) {
			return $html;
		}

		$logo_url = esc_url( $options['dark_mode_logo_url'] );

		return '<div class="astra-child__logo-alt" data-altsrc="' . $logo_url . '"/>' . $html . '</div>';
	}

	/**
	 * Add body class to page.
	 *
	 * @param array $classes An array of classes for the body tag.
	 */
	public function add_body_class( $classes ) {
		$options = get_option( 'astra_child_options' );

		if ( isset( $options['dark_mode_enabled'] ) && isset( $options['default_theme'] ) ) {
			if (
				'dark' === $options['default_theme'] ||
				( ! empty( $_COOKIE['astraChildTheme'] ) && 'dark' == $_COOKIE['astraChildTheme'] ) ) {
				$classes[] = 'darkmode';
			}
		}

		return $classes;
	}
}
