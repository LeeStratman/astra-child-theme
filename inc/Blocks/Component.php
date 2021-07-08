<?php
/**
 * Astra Child blocks functions and definitions.
 *
 * @package Astra Child
 * @since 1.0.0
 */

namespace SWD\Astra_Child\Blocks;

use SWD\Astra_Child\Component_Interface;
use function add_action;
use function add_filter;
use function register_blocks;

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
		return 'blocks';
	}

	/**
	 * Class constructor.
	 */
	public function initialize() {
		add_filter( 'block_categories', array( $this, 'filter_register_categories' ), 10, 2 );
		add_action( 'init', array( $this, 'action_register_blocks' ) );
	}


	/**
	 * Add block categories.
	 *
	 * @param array  $categories an array of block categories.
	 * @param object $post A post object.
	 */
	public function filter_register_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'astra-child',
					'icon'  => null,
					'title' => __( 'Astra Child', 'astra-child' ),
				),
			)
		);
	}

	/**
	 * Register custom WordPress blocks.
	 */
	public function action_register_blocks() {

		// If block editor not supported, bail.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$blocks = array(
			// 'astra-child/icon',
		);

		foreach ( $blocks as $block ) {
			$registered = register_block_type(
				$block,
				array(
					'editor_script' => 'astra-child-block-editor-js',
					'editor_style'  => 'astra-child-theme-editor-css',
					'style'         => 'astra-child-theme-css',
				)
			);
		}

		// Register dynamic block.
		register_block_type(
			'astra-child/showcase',
			array(
				'editor_script'   => 'astra-child-block-editor-js',
				'editor_style'    => 'astra-child-theme-editor-css',
				'style'           => 'astra-child-theme-css',
				'render_callback' => array( $this, 'render_showcase' ),
			)
		);

		// Register dynamic block.
		register_block_type(
			'astra-child/post-showcase',
			array(
				'editor_script'   => 'astra-child-block-editor-js',
				'editor_style'    => 'astra-child-theme-editor-css',
				'style'           => 'astra-child-theme-css',
				'render_callback' => array( $this, 'render_post_showcase' ),
			)
		);

		// Register dynamic block.
		register_block_type(
			'astra-child/category-archive',
			array(
				'editor_script'   => 'astra-child-block-editor-js',
				'editor_style'    => 'astra-child-theme-editor-css',
				'style'           => 'astra-child-theme-css',
				'render_callback' => array( $this, 'render_category_archive' ),
			)
		);

		// Register dynamic block.
		register_block_type(
			'astra-child/team-archive',
			array(
				'editor_script'   => 'astra-child-block-editor-js',
				'editor_style'    => 'astra-child-theme-editor-css',
				'style'           => 'astra-child-theme-css',
				'render_callback' => array( $this, 'render_team_archive' ),
			)
		);
	}

	/**
	 * Render showcase block.
	 *
	 * @param array $attributes The attributes of the block.
	 */
	public function render_showcase( $attributes ) {
		$output = '';

		if ( ! isset( $attributes['linkTypes'] ) ) {
			return $output;
		}

		$output .= "<div class='wp-block__astra-child-showcase alignfull'>";
		$output .= "<nav class='astra-child-showcase__nav'>";
		$output .= "<ul class='astra-child-showcase__list'>";

		if ( ! isset( $attributes['selectedImageFocalPoints'] ) ) {
			$attritbutes['selectedImageFocalPoints'] = array(
				array(
					'x' => 0.5,
					'y' => 0.5,
				),
				array(
					'x' => 0.5,
					'y' => 0.5,
				),
				array(
					'x' => 0.5,
					'y' => 0.5,
				),
				array(
					'x' => 0.5,
					'y' => 0.5,
				),
			);
		}

		foreach ( $attributes['linkTypes'] as $key => $type ) {
			$link         = $attributes['links'][ $key ];
			$image        = $attributes['selectedImages'][ $key ];
			$focal_points = $attributes['selectedImageFocalPoints'][ $key ];
			$focal_x      = $focal_points['x'] * 100 . '%';
			$focal_y      = $focal_points['y'] * 100 . '%';
			$text         = $attributes['text'][ $key ];

			$output .= "<li class='astra-child-showcase__list-item' style='background-image: url($image); background-position: $focal_x, $focal_y;'>";
			$output .= "<div class='astra-child-showcase__overlay'></div>";
			$output .= "<a class='astra-child-showcase__link' href=$link>";
			$output .= "<img src=$image class='astra-child-showcase_desktop-image'>";
			$output .= "<div class='astra-child-showcase__title-wrap'>";
			$output .= "<h2 class='astra-child-showcase__title'>$text</h2></div></a></li>";
		}

		$output .= '</ul></nav></div>';

		return $output;
	}

	/**
	 * Render post showcase block.
	 *
	 * @param array $attributes The attributes of the block.
	 */
	public function render_post_showcase( $attributes ) {
		if ( empty( $attributes['postType'] ) ) {
			return;
		}

		if ( empty( $attributes['selectedPosts'] ) ) {
			return;
		}

		$selected_posts = array();

		foreach ( $attributes['selectedPosts'] as $post ) {
			array_push( $selected_posts, $post['id'] );
		}

		$posts = get_posts(
			array(
				'numberposts' => -1,
				'post_type'   => $attributes['postType'],
				'include'     => $selected_posts,
			)
		);

		$output = '';

		if ( isset( $attributes['align'] ) && 'full' === $attributes['align'] ) {
			$output .= '<div class="wp-block__astra-child-post-showcase alignfull">';
		} else {
			$output .= '<div class="wp-block__astra-child-post-showcase">';
		}

		foreach ( $posts as $post ) {
			$post_title = $this->get_text_excerpt( $post->post_title, 4 );

			// Get post featured image.
			$image = get_the_post_thumbnail( $post->ID );
			$output .= '<div class="post-content">';
			$output .= '<a class="post-content__link" href="' . get_permalink( $post->ID ) . '">';
			$output .= '<div class="post-content__post-thumb">';
			$output .= $image;
			$output .= '</div>';
			$output .= '<div class="post-content__text">';
			$output .= '<h3 class="post-content__title">' . $post_title . '</h3>';
			$output .= '<p class="post-content__excerpt">' . $post->post_excerpt . '</p>';
			$output .= '</div></a>';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Render post showcase block.
	 *
	 * @param array $attributes The attributes of the block.
	 */
	public function render_category_archive( $attributes ) {
		if ( ! $attributes['selectedTaxonomy'] ) {
			return;
		}

		$output = '';

		$terms = get_terms(
			array(
				'taxonomy' => $attributes['selectedTaxonomy'],
				'hide_empty' => ! $attributes['showEmpty'],
			)
		);

		if ( $terms && ! empty( $terms ) ) {

			$output .= '<div class="wp-block__astra-child-category-archive">';

			foreach ( $terms as $term ) {

				$image_id = get_term_meta( $term->term_id, 'featured_image', true );

				$args = array(
					'title'    => $term->name,
					'link'     => get_term_link( $term ),
					'subtitle' => '',
					'content'  => array(
						array(
							'type'  => 'text',
							'value' => $this->get_text_excerpt( $term->description, 30 ),
						),
					),
					'button'   => array(
						'link' => get_term_link( $term ),
						'text' => 'Learn More',
					),
					'image'    => array(
						'id'   => $image_id,
						'size' => 'astra-child-blog-archive-wide',
					),
				);

				$output .= $this->get_taxonomy_archive_layout( $args );

			}

			$output .= '</div>';

		}
		return $output;
	}

	/**
	 * Render team archive block.
	 *
	 * @param array $attributes The attributes of the block.
	 */
	public function render_team_archive( $attributes ) {
		$output = '';

		$team_members = get_posts(
			array(
				'numberposts' => -1,
				'post_type'   => 'astra_child_team',
			)
		);

		if ( $team_members && ! empty( $team_members ) ) {
			$output .= '<div class="wp-block__astra-child-team-archive">';
			foreach ( $team_members as $member ) {
				$permalink = get_permalink( $member->ID );
				$image = get_the_post_thumbnail( $member->ID, 'astra-child-team-member-archive' );

				if ( '' === $image ) {
					$image_url = get_stylesheet_directory_uri() . '/assets/images/team-member-default.png';
					$image = '<img src="' . $image_url . '" class="attachment-astra-child-team-member-archive size-astra-child-team-member-archive wp-post-image" alt="No photo available" loading="lazy">';
				}

				$output .= '<article class="ast-article-post astra-child__team-archive-article">';
				$output .= '<div class="astra-child__single-team-layout">';
				$output .= '<div class="astra-child__team-member-content">';
				$output .= '<header class="astra-child__team-member-header">';
				$output .= '<h2 class="astra-child__team-member-name">';
				$output .= '<a href="' . esc_attr( $permalink ) . '">' . esc_html( $member->post_title ) . '</a>';
				$output .= '</h2>';

				$title = get_post_meta( $member->ID, 'team-member-title', true );

				if ( '' !== $title ) {
					$output .= '<p class="astra-child__team-member-title">' . esc_html( $title ) . '</p>';
				}

				$output .= '</header>';
				$output .= '<div class="entry-content clear article-entry-content-blog-layout-2">';

				$email = get_post_meta( $member->ID, 'team-member-email', true );

				if ( '' !== $email ) {
					$output .= '<a class="astra-child__team-member-contact" href=mailto:"' . esc_html( $email ) . '">Contact Me</a>';
				}

				$output .= '</div>';
				$output .= '<p class="read-more"><a class="ast-button" href="' . esc_attr( $permalink ) . '">Learn More</a></p>';
				$output .= '</div>';
				$output .= '<div class="astra-child__team-member-thumb">' . $image . '</div>';
				$output .= '</div></article>';
			}
			$output .= '</div>';
		}
		return $output;
	}

	/**
	 * Renders taxonomy layout.
	 *
	 * @param array $args The arguments of the post.
	 */
	public function get_taxonomy_archive_layout( $args ) {
		$output = '<article class="ast-article-post astra-child__single-taxonomy-article">';
		$output .= '<div class="astra-child__single-taxonomy-layout">';
		$output .= '<div class="astra-child__single-taxonomy-content">';

		$output .= $this->get_taxonomy_archive_header( $args['title'], $args['link'], $args['subtitle'] );

		$output .= '<div class="astra-child__single-taxonomy-entry-content">';

		$output .= $this->get_taxonomy_archive_content( $args['content'] );

		$output .= $this->get_taxonomy_archive_button( $args['button'] );

		$output .= '</div></div>';

		$output .= $this->get_taxonomy_archive_image( $args['image'], $args['link'] );

		$output .= '</div></article>';

		return $output;
	}

	/**
	 * Create post header.
	 *
	 * @param string $title    The title of the post.
	 * @param string $link     The link to the post.
	 * @param string $subtitle Subtitle of the post.
	 */
	public function get_taxonomy_archive_header( $title, $link = '', $subtitle = '' ) {
		$output = '<header class="astra-child__single-taxonomy-header">';
		$output .= '<h2 class="astra-child__single-taxonomy-title entry-title">';

		if ( '' !== $link ) {
			$output .= '<a class="astra-child__single-taxonomy-link" href="' . esc_attr( $link ) . '">' . esc_html( $title ) . '</a>';
		} else {
			$output .= esc_html( $title );
		}

		$output .= '</h2>';

		if ( '' !== $subtitle ) {
			$output .= '<p class="astra-child__single-taxonomy-subtitle">' . esc_html( $subtitle ) . '</p>';
		}

		$output .= '</header>';

		return $output;
	}

	/**
	 * Get taxonomy archive content.
	 *
	 * @param array $content An array containing content.
	 */
	public function get_taxonomy_archive_content( $content ) {
		$output = '';

		foreach ( $content as $item ) {
			if ( 'link' === $item['type'] ) {
				$output .= '<a class="astra-child__single-taxonomy-content-link" href="' . esc_attr( $item['href'] ) . '">' . $item['value'] . '</a>';
			} else {
				$output .= '<p class="astra-child__single-taxonomy-content-text">' . $item['value'] . '</p>';
			}
		}

		return $output;
	}

	/**
	 * Get taxonomy archive button.
	 *
	 * @param array $button An array of button options.
	 */
	public function get_taxonomy_archive_button( $button = array() ) {
		$output = '';

		if ( ! empty( $button ) ) {
			$output .= '<p class="read-more"><a class="ast-button" href="' . esc_attr( $button['link'] ) . '">' . esc_html( $button['text'] ) . '</a></p>';
		}

		return $output;
	}

	/**
	 * Get taxonomy archive image.
	 *
	 * @param array       $image An array of image options.
	 * @param bool|string $link  Link to surrond the image.
	 */
	public function get_taxonomy_archive_image( $image = array(), $link = false ) {
		$output = '';

		if ( isset( $image['id'] ) ) {
			$size       = $image['size'] ? $image['size'] : '';
			$image_html = wp_get_attachment_image( $image['id'], $size );
		} else {
			return $output;
		}

		if ( $image_html ) {
			$output .= '<div class="astra-child__single-taxonomy-thumb">';
			if ( ! $link ) {
				$output .= $image_url;
			} else {
				$output .= '<a href="' . esc_attr( $link ) . '">' . $image_html . '</a>';
			}
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Get a text excerpt.
	 *
	 * @param string $text      The text to shorten.
	 * @param int    $num_words The number of words to return.
	 */
	public function get_text_excerpt( $text, $num_words = 0 ) {
		$num_words = intval( $num_words );
		if ( ! is_int( $num_words ) ) {
			return $text;
		}

		$post_title_array = explode( ' ', $text );

		if ( count( $post_title_array ) < $num_words ) {
			return $text;
		}

		$post_title_string = '';

		for ( $i = 0; $i < $num_words; $i++ ) {
			$post_title_string .= $post_title_array[ $i ];
			if ( $i < $num_words - 1 ) {
				$post_title_string .= ' ';
			}
		}

		$post_title_string .= '...';

		return $post_title_string;
	}
}
