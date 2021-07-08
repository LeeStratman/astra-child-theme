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
export default registerBlockType("astra-child/post-showcase", {
  title: __("Post Showcase", "astra-child"),
  description: __("Displays specific posts in a grid.", "astra-child"),
  category: "astra-child",
  icon,
  keywords: [__("Post Project Service", "astra-child")],
  attributes: attributes,
  supports: { align: ["full"] },
  edit: edit,
  save: () => {
    return null;
  },
});
