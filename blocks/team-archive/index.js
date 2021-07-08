/**
 * Team Archive Block.
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
export default registerBlockType("astra-child/team-archive", {
  title: __("Team Archive", "astra-child"),
  description: __("Displays an archive of team members.", "astra-child"),
  category: "astra-child",
  icon,
  keywords: [__("team archive", "astra-child")],
  attributes: attributes,
  supports: {},
  edit: edit,
  save: () => {
    return null;
  },
});
