<?php
/**
 * Astra Child Astra functions and definitions.
 *
 * TODO: Form ID comes from options. Move this to plugin module.
 *
 * @package Astra Child
 * @since 1.0.0
 */

namespace SWD\Astra_Child\Gravity_Forms;

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
		return 'gravity-forms';
	}

	/**
	 * Class constructor.
	 */
	public function initialize() {
		// This script should be placed in the theme's header.php file just before the wp_head() function is called.
		add_action( 'astra_head_top', array( $this, 'add_gravity_form_script' ) );
		add_action( 'astra_body_bottom', array( $this, 'add_gravity_form' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_styles' ), 15 );
		add_filter( 'body_class', array( $this, 'filter_astra_body_classes' ) );
	}

	/**
	 * Adds classes to body tag.
	 *
	 * @param array $classes An array of current classes.
	 */
	public function filter_astra_body_classes( $classes ) {
		// TODO: Check if side form is active on page.
		if ( true ) {
			$classes[] = 'astra-child__has-side-form';
		}

		return $classes;
	}

	/**
	 * Registers or enqueues stylesheets.
	 */
	public function action_enqueue_styles() {
		$js_path = '/assets/js/side-form.js';
		// Check if form is being displayed.
		wp_enqueue_script(
			'astra-child-side-form-js',
			get_stylesheet_directory_uri() . $js_path,
			array(),
			filemtime( get_stylesheet_directory() . $js_path ),
			true
		);
	}

	/**
	 * Adds script for gravity form.
	 */
	public function add_gravity_form_script() {
		// Get form id from options.
		$form_id = 1;
		$is_ajax = true;
		gravity_form_enqueue_scripts( $form_id, $is_ajax );
	}

	/**
	 * Adds gravity form.
	 */
	public function add_gravity_form() {
		// Get form id.
		$form_id = 1;

		$display_title = false;

		$display_description = false;
		$display_inactive = false;
		$field_values = false;
		$ajax = true;
		$tabindex = 0;
		$echo = false;

		$form = gravity_form(
			$form_id,
			$display_title,
			$display_description,
			$display_inactive,
			$field_values,
			$ajax,
			$tabindex,
			$echo
		);
		?>
		<div class="astra-child__side-form-wrapper">
			<button aria-label="Contact Us" type="button" tabindex="0" class="astra-child__side-form-button">
				<span class="astra-child__side-form-button-arrow">&#10142;</span>Connect with Us
			</button>
			<?php echo $form; ?>
		</div>
		<?php
	}
}
