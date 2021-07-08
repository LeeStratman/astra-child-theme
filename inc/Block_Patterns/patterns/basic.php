<?php
/**
 * General Content block pattern.
 *
 * @package Astra Child
 */

return array(
	'title'         => __( 'General Content', 'astra-child' ),
	'description'   => _x( 'A Heading with a paragraph.', 'Block pattern description', 'astra-child' ),
	// phpcs:disable
	'content'       => "<!-- wp:group {\"verticalSpacing\":\"l\"} -->\n<div class=\"wp-block-group has-vertical-spacing-l \"><div class=\"wp-block-group__inner-container\"><!-- wp:heading -->\n<h2>Heading</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:group -->",
	// phpcs:enable
	'categories'    => array( 'basic' ),
	'viewportWidth' => 1500,
);
