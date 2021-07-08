const { registerBlockStyle, unregisterBlockStyle } = wp.blocks;

/**
 * Register custom WordPress block styles.
 *
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/
 */

/**
 * Core Button
 */
wp.domReady(() => {
  registerBlockStyle("core/button", [
    {
      name: "primary",
      label: "Primary",
      isDefault: true,
    },
    {
      name: "secondary",
      label: "Secondary",
    },
  ]);

  /**
   * There is a race condition, so make sure to run unregister function
   * last.
   */
  unregisterBlockStyle("core/button", [
    "default",
    "outline",
    "squared",
    "fill",
  ]);
});
