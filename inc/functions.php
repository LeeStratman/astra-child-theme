<?php
/**
 * The `astra_child()` function.
 *
 * @package Astra Child
 */

namespace SWD\Astra_Child;

/**
 * Initialize the theme.
 */
function astra_child() {
	static $theme = null;

	if ( null === $theme ) {
		$theme = new Theme();
		$theme->initialize();
	}
}
