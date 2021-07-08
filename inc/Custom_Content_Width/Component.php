<?php
/**
 * Astra Child custom spacing functions and definitions.
 *
 * @package Astra Child
 * @since 1.0.0
 */

namespace SWD\Astra_Child\Custom_Content_Width;

use SWD\Astra_Child\Component_Interface;
use function add_action;
use function add_filter;
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
		return 'custom-content-width';
	}

	/**
	 * Class constructor.
	 */
	public function initialize() {
		add_filter( 'body_class', array( $this, 'add_primary_content_class' ) );
		add_filter( 'astra_meta_box_options', array( $this, 'add_top_spacing_meta_box' ), 1, 10 );
		add_action( 'astra_meta_box_markup_before', array( $this, 'add_metabox_markup' ), 1, 10 );
	}

	/**
	 * Add class to Astra's primary content.
	 *
	 * @param array $classes An array of classes.
	 */
	public function add_primary_content_class( $classes ) {

		// If page has an option selected, add 'no-top-spacing'.
		$post_content_width = get_post_meta( get_the_ID(), 'post-content-width', true );

		if ( 'wide' === $post_content_width ) {
			$classes[] = 'astra-child__wide-view';
		}

		return $classes;
	}

	/**
	 * Adds a metabox to the list of Astra page options.
	 *
	 * @param array $options An array of metabox options.
	 */
	public function add_top_spacing_meta_box( $options ) {
		$options['post-content-width'] = array( 'sanitize' => 'FILTER_DEFAULT' );
		return $options;
	}

	/**
	 * Insert meta box markup.
	 *
	 * @param array $meta Metabox default values.
	 */
	public function add_metabox_markup( $meta ) {
		$post_content_width = ( isset( $meta['post-content-width']['default'] ) ) ? $meta['post-content-width']['default'] : ''; ?>

		<div class="site-sidebar-layout-meta-wrap components-base-control__field">
			<p class="post-attributes-label-wrapper" >
				<strong> <?php esc_html_e( 'Content Width', 'astra-child' ); ?> </strong>
			</p>
			<div class="post-content-width-option-wrap">
				<select name="post-content-width" id="post-content-width">
					<option value="normal" <?php selected( $post_content_width, 'default' ); ?> > <?php esc_html_e( 'Default', 'astra-child' ); ?></option>
					<option value="wide" <?php selected( $post_content_width, 'left-sidebar' ); ?> > <?php esc_html_e( 'Wide', 'astra-child' ); ?></option>
				</select>
			</div>
		</div>
		<?php
	}
}
