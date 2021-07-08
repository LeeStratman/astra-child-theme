<?php
/**
 * Project details block pattern.
 *
 * @package Astra Child
 */

return array(
	'title'         => __( 'Project Details', 'astra-child' ),
	'description'   => _x( 'A table with project details.', 'Block pattern description', 'astra-child' ),
	// phpcs:disable
	'content'       => "<!-- wp:table {\"className\":\"is-block-pattern__project-details\"} -->\n<figure class=\"wp-block-table is-block-pattern__project-details\"><table><tbody><tr><td class=\"has-text-align-right\" data-align=\"right\">Sector:</td><td>Health Care</td></tr><tr><td class=\"has-text-align-right\" data-align=\"right\">Client:</td><td>Whitewater Health Systems</td></tr><tr><td class=\"has-text-align-right\" data-align=\"right\">Services:</td><td>Design, Surveying, Planning</td></tr><tr><td class=\"has-text-align-right\" data-align=\"right\">Project Lead:</td><td>Colten Tuescher</td></tr><tr><td class=\"has-text-align-right\" data-align=\"right\">Location:</td><td>Whitewater, WI</td></tr><tr><td class=\"has-text-align-right\" data-align=\"right\">Value:</td><td>$250.6 - $267.4M</td></tr><tr><td class=\"has-text-align-right\" data-align=\"right\">Size:</td><td>2.2M ft<sup>2</sup></td></tr><tr><td class=\"has-text-align-right\" data-align=\"right\">Duration:</td><td>Mar. 2014 - Feb. 2016</td></tr></tbody></table></figure>\n<!-- /wp:table -->",
	// phpcs:enable
	'categories'    => array( 'basic' ),
);
