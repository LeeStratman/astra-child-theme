/**
 * Edit function for Showcase Block.
 */

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { withSelect } = wp.data;
const { InspectorControls, URLInput, MediaPlaceholder } = wp.blockEditor;
const { PanelBody, SelectControl, TextControl } = wp.components;

/**
 * Module constants.
 */
const ALLOWED_MEDIA_TYPES = ["image"];

/**
 * Internal dependencies
 */
import { activeShowcases, setImages } from "./shared";
import ShowcaseControls from "./components/ShowcaseControls";

/**
 * Showcase Edit function component.
 */
function ShowcaseEdit(props) {
  const { attributes, setAttributes, taxonomies, terms } = props;
  const {
    selectedTaxonomies,
    links,
    linkTypes,
    selectedTerms,
    selectedImages,
    selectedImageFocalPoints,
    text,
  } = attributes;

  const showcaseControlProps = {
    setAttributes,
    linkTypes,
    selectedTaxonomies,
    selectedTerms,
    links,
    text,
    selectedImages,
    selectedImageFocalPoints,
    taxonomies,
    terms,
  };

  const getBackground = (index) => {
    if (selectedImages[index] && "" !== selectedImages[index]) {
      return {
        backgroundImage: `url("${selectedImages[index]}")`,
        backgroundPosition: `${selectedImageFocalPoints[index].x * 100}% ${
          selectedImageFocalPoints[index].y * 100
        }%`,
      };
    }

    return {};
  };

  const getLink = (index) => {
    if (links[index] && "" !== links[index]) {
      return links[index];
    }

    return "#";
  };

  const hasImage = (index) => {
    return selectedImages[index] && "" !== selectedImages[index];
  };

  return (
    <>
      <InspectorControls>
        <ShowcaseControls {...showcaseControlProps} />
      </InspectorControls>
      <div className="wp-block__astra-child-showcase">
        <nav className="astra-child-showcase__nav">
          <ul className="astra-child-showcase__list">
            {activeShowcases.map((active, index) => {
              return (
                <li
                  key={activeShowcases[index]}
                  className="astra-child-showcase__list-item"
                  style={getBackground(index)}
                >
                  <div className="astra-child-showcase__overlay"></div>
                  <div className="astra-child-showcase__link">
                    {hasImage(index) && (
                      <img
                        src={selectedImages[index]}
                        className="desktop-only"
                      />
                    )}
                    {!hasImage(index) && (
                      <MediaPlaceholder
                        labels={{
                          title: __("Showcase Image", "astra-child"),
                          instructions: __(
                            "Upload an image or video file, or pick one from your media library."
                          ),
                        }}
                        onSelect={(imageObject) => {
                          setImages(
                            imageObject,
                            index,
                            selectedImages,
                            setAttributes
                          );
                        }}
                        accept="image/*,video/*"
                        allowedTypes={ALLOWED_MEDIA_TYPES}
                      ></MediaPlaceholder>
                    )}
                    <div className="astra-child-showcase__title-wrap">
                      <h1 className="astra-child-showcase__title">
                        {text[index]}
                      </h1>
                    </div>
                  </div>
                </li>
              );
            })}
          </ul>
        </nav>
      </div>
    </>
  );
}

/**
 * Higher-order component used to fetch WordPress state and provide them
 * as props to the edit function.
 *
 * withSelect is a function that returns a higher-order component that your edit
 * function (component) gets passed into.
 */
export default withSelect((select, props) => {
  // Get Taxonomies.
  const { selectedTaxonomies } = props.attributes;

  // Get available taxonomies.
  const taxonomies = select("core").getTaxonomies();

  // Get available terms from selected taxonomies.
  const terms = selectedTaxonomies.map((taxonomy, index) => {
    return select("core").getEntityRecords("taxonomy", taxonomy);
  });

  return { taxonomies, terms };
})(ShowcaseEdit);
