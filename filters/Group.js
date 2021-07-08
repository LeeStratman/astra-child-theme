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
addFilter("blocks.registerBlockType", "core/group", (settings, name) => {
  if ("core/group" !== name) {
    return settings;
  }

  const attributes = {
    ...settings.attributes,
    verticalSpacing: {
      type: "string",
      default: "none",
    },
    contentWidth: {
      type: "string",
      default: "none",
    },
  };

  return { ...settings, attributes };
});

const addInspectorControls = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    if ("core/group" !== props.name) {
      return <BlockEdit {...props} />;
    }

    const { attributes, setAttributes } = props;

    return (
      <Fragment>
        <BlockEdit {...props} />
        <InspectorControls>
          <PanelBody title={__("Spacing")}>
            <SelectControl
              label={__("Vertical Spacing")}
              value={attributes.verticalSpacing}
              onChange={(spacing) =>
                setAttributes({ verticalSpacing: spacing })
              }
              options={[
                { label: __("None", "astra-child"), value: "n" },
                { label: __("Small", "astra-child"), value: "s" },
                { label: __("Medium", "astra-child"), value: "m" },
                { label: __("Large", "astra-child"), value: "l" },
              ]}
            />
            <SelectControl
              label={__("Content Width")}
              value={attributes.contentWidth}
              onChange={(spacing) => setAttributes({ contentWidth: spacing })}
              options={[
                { label: __("Full", "astra-child"), value: "n" },
                { label: __("90%", "astra-child"), value: "s" },
                { label: __("85%", "astra-child"), value: "m" },
                { label: __("80%", "astra-child"), value: "l" },
              ]}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>
    );
  };
}, "addInspectorControls");
addFilter("editor.BlockEdit", "core/group", addInspectorControls);

const setExtraPropsToBlockType = (props, blockType, attributes) => {
  if ("core/group" !== blockType.name) {
    return props;
  }

  const notDefined =
    typeof props.className === "undefined" || !props.className ? true : false;

  const verticalSpacing =
    attributes.verticalSpacing === "none"
      ? ""
      : `has-vertical-spacing-${attributes.verticalSpacing}`;

  const contentWidth =
    attributes.contentWidth === "none"
      ? ""
      : `has-horizontal-spacing-${attributes.contentWidth}`;

  return Object.assign(props, {
    className: notDefined
      ? `${verticalSpacing} ${contentWidth}`
      : `${props.className} ${verticalSpacing} ${contentWidth}`,
  });
};

addFilter(
  "blocks.getSaveContent.extraProps",
  "astra-child/group",
  setExtraPropsToBlockType
);

const addGroupClass = createHigherOrderComponent((BlockListBlock) => {
  return (props) => {
    if (props.block.name === "core/group") {
      const { attributes } = props;

      const verticalSpacing =
        attributes.verticalSpacing === "none"
          ? ""
          : `has-vertical-spacing-${attributes.verticalSpacing}`;

      const contentWidth =
        attributes.contentWidth === "none"
          ? ""
          : `has-horizontal-spacing-${attributes.contentWidth}`;

      return (
        <BlockListBlock
          {...props}
          className={`${contentWidth} ${verticalSpacing}`}
        />
      );
    }
    return <BlockListBlock {...props} />;
  };
});

addFilter(
  "editor.BlockListBlock",
  "astra-child/addGroupClassNameEditor",
  addGroupClass
);
