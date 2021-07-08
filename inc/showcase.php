<?php
/**
 * Helper functions.
 *
 * @package Astra Child
 */

/**
 * Retrieves image for showcase layout.
 */
function astra_child_showcase_image() {

	$image_url = astra_child_get_post_image_url();

	if ( ! $image_url ) {
		echo "<div class='astra-child__showcase-archive__featured-image astra-child__showcase-archive__no-image'>";
	} else {
		echo "<div class='astra-child__showcase-archive__featured-image' style='background-image: url(" . esc_url( $image_url ) . ");'>";
	}
}


/**
 * Retrieves content from showcase content.
 */
function astra_child_showcase_content() {

	astra_child_showcase_image();

	echo '<div class="astra-child__showcase-archive__overlay"><div class="astra-child__showcase-archive__inner-wrap">';

	astra_child_showcase_title();
	echo apply_filters( 'astra-child-taxonomy-layout-1-after-title', '' );

	echo '</div></div></div>';
}

/**
 * Displays showcase title.
 */
function astra_child_showcase_title() {
	$title = get_the_title();

	if ( ! $title ) {
		$title = __( 'No title', 'astra-child' );
	}

	echo "<h2 class='entry-title astra-child__showcase-archive__title'>" . esc_html( $title ) . '</h2>';
}

/**
 * Retrieves URL for post.
 */
function astra_child_get_post_image_url() {

	$id = get_the_ID();

	if ( ! $id ) {
		return false;
	}

	$image_url = astra_child_get_archive_image_url( $id );

	if ( ! $image_url ) {
		$image_url = get_the_post_thumbnail_url(
			$id,
			'astra-child-showcase-archive',
		);

		if ( ! $image_url ) {
			return false;
		}
	}

	return $image_url;
}

/**
 * Get archive image url.
 *
 * @param int $id The id of the post.
 */
function astra_child_get_archive_image_url( $id ) {

	$image_archive_id = get_post_meta( $id, 'archive_thumbnail_id', true );

	/**
	 * Invalid post id.
	 */
	if ( ! $image_archive_id ) {
		return false;
	}

	/**
	 * Archive image id doesn't exist.
	 */
	if ( '' === $image_archive_id ) {
		return false;
	}

	$image_url = wp_get_attachment_image_src( $image_archive_id, 'astra-child-showcase-archive' );

	if ( ! $image_url ) {
		return false;
	}

	return $image_url[0];
}
