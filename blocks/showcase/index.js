/**
 * Showcase Block.
 */

/**
 * Block dependencies.
 */
import icon from "./icon";
import attributes from "./attributes";
import edit from "./edit";

/**
 * Block libraries.
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

/**
 * Register block
 */
export default registerBlockType("astra-child/showcase", {
  title: __("Showcase", "astra-child"),
  description: __("Displays 4 image/text links.", "astra-child"),
  category: "astra-child",
  icon,
  keywords: [__("Terms Categories Showcase", "astra-child")],
  attributes: attributes,
  supports: { align: ["full"] },
  edit: edit,
  save: () => {
    return null;
  },
});
