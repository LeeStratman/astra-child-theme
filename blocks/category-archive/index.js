/**
 * Category Archive Block.
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
export default registerBlockType("astra-child/category-archive", {
  title: __("Category Archive", "astra-child"),
  description: __("Displays an archive for a chosen category.", "astra-child"),
  category: "astra-child",
  icon,
  keywords: [__("archive category", "astra-child")],
  attributes: attributes,
  supports: { align: ["full", "wide"] },
  edit: edit,
  save: () => {
    return null;
  },
});
