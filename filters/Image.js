const { __ } = wp.i18n;
const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment, cloneElement } = wp.element;
const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { PanelBody, Button } = wp.components;

/**
 * Used to filter the block settings.
 *
 * It receives the block settings and the name of the registered block as arguments.
 * @see https://developer.wordpress.org/block-editor/developers/filters/block-filters/#blocks-registerblocktype
 */
addFilter("blocks.registerBlockType", "core/image", (settings, name) => {
  if ("core/image" !== name) {
    return settings;
  }

  const attributes = {
    ...settings.attributes,
    altImage: {
      type: "object",
      default: { id: null, url: null },
    },
  };

  return { ...settings, attributes };
});

const addInspectorControls = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    if ("core/image" !== props.name) {
      return <BlockEdit {...props} />;
    }

    const { attributes, setAttributes } = props;

    const { altImage } = attributes;

    const setImage = (image) => {
      setAttributes({ altImage: { id: image.id, url: image.sizes.full.url } });
    };

    const removeImage = () => {
      setAttributes({ altImage: { id: null, url: null } });
    };

    return (
      <Fragment>
        <BlockEdit {...props} />
        <InspectorControls>
          <PanelBody title={__("Dark Mode Image")}>
            <MediaUploadCheck>
              {!!altImage.id && (
                <MediaUpload
                  title={__("Set Dark Mode Image ")}
                  onSelect={(image) => setImage(image)}
                  type="image"
                  value={altImage.id}
                  render={({ open }) => (
                    <Button
                      style={{
                        textAlign: "center",
                        marginBottom: "5px",
                      }}
                      onClick={open}
                      isSecondary
                    >
                      <img
                        style={{ width: "80px" }}
                        src={altImage.url}
                        alt={__("Dark mode image")}
                      />
                    </Button>
                  )}
                />
              )}
              {!!altImage.id && (
                <div>
                  <Button
                    className="editor-button__slide-remove"
                    onClick={() => removeImage()}
                    isLink
                    isDestructive
                  >
                    {__("Remove Image", "astra-child")}
                  </Button>
                </div>
              )}
              {!altImage.id && (
                <MediaUpload
                  onSelect={(image) => {
                    setImage(image);
                  }}
                  value={altImage.id}
                  render={({ open }) => (
                    <Button
                      style={{
                        textAlign: "center",
                        marginBottom: "5px",
                      }}
                      onClick={open}
                      isSecondary
                    >
                      {__("Select Image", "astra-child")}
                    </Button>
                  )}
                />
              )}
            </MediaUploadCheck>
          </PanelBody>
        </InspectorControls>
      </Fragment>
    );
  };
}, "addInspectorControls");
addFilter("editor.BlockEdit", "core/image", addInspectorControls);

const setExtraPropsToBlockType = (props, blockType, attributes) => {
  if ("core/image" !== blockType.name) {
    return props;
  }

  const { altImage } = attributes;

  return Object.assign(props, {
    "data-altsrc": `${altImage.url}`,
  });
};

addFilter(
  "blocks.getSaveContent.extraProps",
  "astra-child/imageExtraProps",
  setExtraPropsToBlockType
);

const addImageAltImage = createHigherOrderComponent((BlockListBlock) => {
  return (props) => {
    if (props.block.name === "core/image") {
      const { attributes } = props;

      const { altImage } = attributes;

      return <BlockListBlock {...props} data-altsrc={`${altImage.url}`} />;
    }
    return <BlockListBlock {...props} />;
  };
});

addFilter("editor.BlockListBlock", "astra-child/addImageAlt", addImageAltImage);
