<?php
/**
 * A theme component represents added functionality to theme. This interface allows
 * each component to integrate with WordPress.
 *
 * @package Astra Child
 */

namespace SWD\Astra_Child;

/**
 * Interface for a theme component.
 */
interface Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize();
}
