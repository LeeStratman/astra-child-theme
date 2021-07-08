const { __ } = wp.i18n;
const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment, cloneElement } = wp.element;
const { InspectorControls, URLInput } = wp.blockEditor;
const {
  PanelBody,
  ToggleControl,
  TextControl,
  RangeControl,
  SelectControl,
} = wp.components;

/**
 * Used to filter the block settings.
 *
 * It receives the block settings and the name of the registered block as arguments.
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#blocks-registerblocktype
 */
addFilter("blocks.registerBlockType", "core/columns", (settings, name) => {
  if ("core/columns" !== name) {
    return settings;
  }

  const attributes = {
    ...settings.attributes,
    mobileReverse: {
      type: "boolean",
      default: false,
    },
  };

  return { ...settings, attributes };
});

const addInspectorControls = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    if ("core/columns" !== props.name) {
      return <BlockEdit {...props} />;
    }

    const { attributes, setAttributes } = props;

    return (
      <Fragment>
        <BlockEdit {...props} />
        <InspectorControls>
          <PanelBody>
            <ToggleControl
              label={__("Reverse Mobile", "astra-child")}
              checked={attributes.mobileReverse}
              onChange={() =>
                setAttributes({ mobileReverse: !attributes.mobileReverse })
              }
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>
    );
  };
}, "addInspectorControls");
addFilter("editor.BlockEdit", "core/columns", addInspectorControls);

const setExtraPropsToBlockType = (props, blockType, attributes) => {
  if ("core/columns" !== blockType.name) {
    return props;
  }

  const notDefined =
    typeof props.className === "undefined" || !props.className ? true : false;

  const isMobileReversed = !attributes.mobileReverse
    ? ""
    : "is-style-mobile-reversed";

  return Object.assign(props, {
    className: notDefined
      ? `${isMobileReversed}`
      : `${props.className} ${isMobileReversed}`,
  });
};

addFilter(
  "blocks.getSaveContent.extraProps",
  "astra-child/columns",
  setExtraPropsToBlockType
);
