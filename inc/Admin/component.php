<?php
/**
 * Admin settings helper
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0
 */

namespace SWD\Astra_Child\Admin;

use SWD\Astra_Child\Component_Interface;

/**
 * Astra Child Admin Settings.
 */
class Component implements Component_Interface {

	/**
	 * Plugin slug
	 *
	 * @since 1.0
	 * @var array $plugin_slug
	 */
	public static $plugin_slug = 'astra-child';

	/**
	 * Default Menu position
	 *
	 * @since 1.0
	 * @var array $default_menu_position
	 */
	public static $default_menu_position = 'themes.php';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'admin';
	}

	/**
	 * Class constructor.
	 */
	public function initialize() {

		if ( ! is_admin() ) {
			return;
		}

		add_action( 'after_setup_theme', array( $this, 'init_admin_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 101 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Admin settings init.
	 */
	public function init_admin_settings() {

	}

	/**
	 * Save all admin settings here.
	 */
	public function save_settings() {

	}

	/**
	 * Register admin scripts.
	 *
	 * @param String $hook Screen name where the hook is fired.
	 * @return void
	 */
	public function register_scripts( $hook ) {

	}

	/**
	 * Add main menu.
	 */
	public function add_admin_menu() {
		$menu_page_title = __( 'Astra Child Options', 'astra-child' );
		$page_title      = __( 'Astra Child', 'astra-child' );

		add_theme_page(
			$menu_page_title,
			$page_title,
			'manage_options',
			'astra-child',
			array(
				$this,
				'menu_callback',
			)
		);
	}

	/**
	 * Menu callback.
	 */
	public function menu_callback() {
		?>

		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">

				<?php settings_fields( 'astra_child_dark_mode', 'astra_child_options' ); ?>

				<?php do_settings_sections( 'astra-child' ); ?>

				<?php submit_button(); ?>

			</form>
		</div>
		<?php
	}

	/**
	 * Register Theme Settings.
	 */
	public function register_settings() {
		register_setting( 'astra_child_dark_mode', 'astra_child_options' );

		add_settings_section(
			'astra_child_section_dark_mode',
			__( 'Dark Mode', 'astra-child' ),
			array( $this, 'dark_mode_callback' ),
			'astra-child'
		);

		add_settings_field(
			'dark_mode_enabled',
			__( 'Enable Dark Mode', 'astra-child' ),
			array( $this, 'checkbox_callback' ),
			'astra-child',
			'astra_child_section_dark_mode',
			array(
				'id' => 'dark_mode_enabled',
			)
		);

		add_settings_field(
			'default_theme',
			__( 'Default Theme', 'astra-child' ),
			array( $this, 'default_theme_callback' ),
			'astra-child',
			'astra_child_section_dark_mode',
			array(
				'id' => 'default_theme',
			)
		);

		add_settings_field(
			'dark_mode_logo_url',
			__( 'Alt Logo URL', 'astra-child' ),
			array( $this, 'text_callback' ),
			'astra-child',
			'astra_child_section_dark_mode',
			array(
				'id' => 'dark_mode_logo_url',
			)
		);
	}

	/**
	 * Dark mode callback. Displays html.
	 *
	 * @param array $args An arrray of the following: title, it, and callback.
	 */
	public function dark_mode_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html_e( 'Dark Mode description here.', 'astra-child' ); ?></p>
		<?php
	}

	/**
	 * Default theme setting callback.
	 *
	 * @param array $args an array with the following: label_for, class.
	 */
	public function default_theme_callback( $args ) {
		$dark_mode_options = get_option( 'astra_child_options' );
		$options = array(
			array(
				'value' => 'light',
				'label' => 'Light',
			),
			array(
				'value' => 'dark',
				'label' => 'Dark',
			),
		);

		$id    = isset( $args['id'] ) ? $args['id'] : '';
		$label = isset( $args['label'] ) ? $args['label'] : '';
		$value = isset( $dark_mode_options[ $id ] ) ? sanitize_text_field( $dark_mode_options[ $id ] ) : 'Light';

		echo '<label class="astra-child-settings-label" for="astra_child_options_' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
		echo '<select id="astra_child_options_' . esc_attr( $id ) . '" name="astra_child_options[' . esc_attr( $id ) . ']" value="' . esc_attr( $value ) . '">';

		foreach ( $options as $option ) {
			echo '<option value="' . esc_attr( $option['value'] ) . '" ' . selected( $value, $option['value'], true ) . '/>' . esc_html( $option['label'] ) . '</option>';
		}

		echo '</select>';
	}

	/**
	 * Checkbox Option Callback. Displays a checkbox.
	 *
	 * @param array $args an array of options.
	 */
	public function checkbox_callback( $args ) {
		$options = get_option( 'astra_child_options' );

		$id    = isset( $args['id'] ) ? $args['id'] : '';
		$label = isset( $args['label'] ) ? $args['label'] : '';
		$value = isset( $options[ $id ] ) ? sanitize_text_field( $options[ $id ] ) : false;

		echo '<label class="astra-child-settings-label" for="astra_child_options_' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
		echo '<input id="astra_child_options_' . esc_attr( $id ) . '" name="astra_child_options[' . esc_attr( $id ) . ']" type="checkbox" value="' . esc_attr( $id ) . '"' . checked( $id, $value, false ) . '>';

	}

	/**
	 * Text Option Callback. Displays a text field.
	 *
	 * @param array $args an array of options.
	 */
	public function text_callback( $args ) {
		$options = get_option( 'astra_child_options' );

		$id    = isset( $args['id'] ) ? $args['id'] : '';
		$label = isset( $args['label'] ) ? $args['label'] : '';
		$value = isset( $options[ $id ] ) ? sanitize_text_field( $options[ $id ] ) : '';

		echo '<label class="astra-child-settings-label" for="astra_child_options_' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
		echo '<input id="astra_child_options_' . esc_attr( $id ) . '" name="astra_child_options[' . esc_attr( $id ) . ']" type="text" value="' . esc_attr( $value ) . '">';

	}
}
