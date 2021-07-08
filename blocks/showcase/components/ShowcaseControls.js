/**
 * Represents a single showcase option panel.
 */

/**
 * Block dependencies.
 */
const { __ } = wp.i18n;
const { URLInput, MediaUpload, MediaUploadCheck } = wp.blockEditor;
const {
  PanelBody,
  SelectControl,
  TextControl,
  FocalPointPicker,
  Button,
} = wp.components;

/**
 * Internal dependencies.
 */
import { activeShowcases, setImages } from "../shared";

function ShowcaseControls({
  setAttributes,
  linkTypes,
  selectedTaxonomies,
  selectedTerms,
  selectedImages,
  selectedImageFocalPoints,
  links,
  text,
  taxonomies,
  terms,
}) {
  /**
   * Dropdown options for link type (source).
   */
  const SOURCE_OPTIONS = [
    { label: "Page", value: "page" },
    { label: "Taxonomy", value: "taxonomy" },
    { label: "Custom", value: "custom" },
  ];

  /**
   * dropDownOptions creates an array of options for
   * the SelectControl component.
   *
   * @param {array}  array An array of objects.
   * @param {string} label The object key for the label.
   * @param {string} value The object key for the value.
   */
  function dropDownOptions(array, label, value) {
    if (!Array.isArray(array) || !array || array.length < 1) {
      return [{ label: "No options available", value: "" }];
    }

    return array.reduce(
      (accumulator, item) => {
        if (!item) {
          return [...accumulator];
        }
        return [...accumulator, { label: item[label], value: item[value] }];
      },
      [{ label: "Choose an option", value: "" }]
    );
  }

  /**
   * Format taxonomy dropdown options object.
   */
  const taxOptions = dropDownOptions(taxonomies, "name", "slug");

  /**
   * Format term dropdown options object.
   */
  const termOptions = (index) => dropDownOptions(terms[index], "name", "id");

  /**
   * Updates the link types.
   *
   * @param {string} value The source of the link.
   * @param {number} index The index to update.
   */
  const setLinkType = (value, index) => {
    let linkTypesCopy = linkTypes.slice();
    linkTypesCopy[index] = value;

    // Clear Taxonomy
    let selectedTaxonomiesCopy = selectedTaxonomies.slice();
    selectedTaxonomiesCopy[index] = "";

    // Clear link.
    let linksCopy = links.slice();
    linksCopy[index] = "";

    // Clear Term.
    let selectedTermsCopy = selectedTerms.slice();
    selectedTermsCopy[index] = "";

    setAttributes({
      linkTypes: linkTypesCopy,
      selectedTaxonomies: selectedTaxonomiesCopy,
      selectedTerms: selectedTermsCopy,
      links: linksCopy,
    });
  };

  /**
   * Updates the selected taxonomies.
   *
   * @param {string} value The taxonomy slug.
   * @param {number} index The index to update.
   */
  const setSelectedTaxonomies = (value, index) => {
    let selectedTaxonomiesCopy = selectedTaxonomies.slice();
    selectedTaxonomiesCopy[index] = value;

    // Clear terms
    let selectedTermsCopy = selectedTerms.slice();
    selectedTermsCopy[index] = "";

    // Clear link
    let linksCopy = links.slice();
    linksCopy[index] = "";

    setAttributes({
      selectedTaxonomies: selectedTaxonomiesCopy,
      selectedTerms: selectedTermsCopy,
      links: linksCopy,
    });
  };

  /**
   * Updates the terms.
   *
   * @param {string} value The term id.
   * @param {number} index The index to update.
   */
  const setTerms = (value, index) => {
    // First, set terms.
    let selectedTermsCopy = selectedTerms.slice();
    selectedTermsCopy[index] = value;

    // Second, set link.
    let linksCopy = links.slice();

    // Get term url.
    const termLink = terms[index].reduce((accumulator, term) => {
      if (term.id === Number(value)) {
        return term.link;
      } else {
        return accumulator;
      }
    }, "");

    if (typeof termLink != "string") {
      termLink = "";
    }

    linksCopy[index] = termLink;

    setAttributes({ selectedTerms: selectedTermsCopy, links: linksCopy });
  };

  /**
   * Updates the links.
   *
   * @param {string} value The URL.
   * @param {number} index The index to update.
   */
  const setLinks = (value, index) => {
    let linksCopy = links.slice();
    linksCopy[index] = value;
    setAttributes({ links: linksCopy });
  };

  /**
   * Updates text.
   *
   * @param {string} value The text.
   * @param {number} index The index to update.
   */
  const setText = (value, index) => {
    let textCopy = text.slice();
    textCopy[index] = value;
    setAttributes({ text: textCopy });
  };

  /**
   * Updates image focal points.
   *
   * @param {string} value The image object.
   * @param {number} index The index to update.
   */
  const setImageFocalPoints = (value, index) => {
    let selectedImageFocalPointsCopy = selectedImageFocalPoints.map(
      (points, index) => {
        return Object.assign({}, points);
      }
    );
    selectedImageFocalPointsCopy[index] = value;
    setAttributes({ selectedImageFocalPoints: selectedImageFocalPointsCopy });
  };

  const isTaxonomy = (index) =>
    "taxonomy" === linkTypes[index] ? true : false;
  const isCustom = (index) => ("custom" === linkTypes[index] ? true : false);
  const hasTaxonomy = (index) => (selectedTaxonomies[index] ? true : false);

  return (
    <>
      {activeShowcases.map((active, index) => {
        return (
          <PanelBody
            key={activeShowcases[index]}
            title={`Showcase  ${index + 1}`}
          >
            <SelectControl
              label={__("Source", "astra-child")}
              value={linkTypes[index]}
              options={SOURCE_OPTIONS}
              help={__("Choose a link source.", "astra-child")}
              onChange={(value) => {
                setLinkType(value, index);
              }}
            />
            {isTaxonomy(index) && (
              <SelectControl
                label={__("Taxonomy", "astra-child")}
                value={selectedTaxonomies[index]}
                options={taxOptions}
                help={__("Choose a taxonomy.", "astra-child")}
                onChange={(value) => {
                  setSelectedTaxonomies(value, index);
                }}
              />
            )}
            {isTaxonomy(index) && hasTaxonomy(index) && (
              <SelectControl
                label={__("Term", "astra-child")}
                value={selectedTerms[index]}
                options={termOptions(index)}
                help={__("Choose a term.", "astra-child")}
                onChange={(value) => {
                  setTerms(value, index);
                }}
              />
            )}
            <URLInput
              label={__("Link", "astra-child")}
              value={links[index]}
              onChange={(url, post) => {
                if (!isTaxonomy(index)) {
                  setLinks(url, index);
                }
              }}
              isFullWidth={true}
              autoFocus={false}
              disableSuggestions={isCustom(index)}
            />
            <TextControl
              label={__("Text", "astra-child")}
              value={text[index]}
              onChange={(value) => {
                setText(value, index);
              }}
            />
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(imageObject) => {
                  setImages(imageObject, index, selectedImages, setAttributes);
                }}
                value={selectedImages[index]}
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
            </MediaUploadCheck>
            <FocalPointPicker
              url={selectedImages[index]}
              dimensions={{ width: 400, height: 100 }}
              value={selectedImageFocalPoints[index]}
              onChange={(value) => {
                setImageFocalPoints(value, index);
              }}
            />
          </PanelBody>
        );
      })}
    </>
  );
}

export default ShowcaseControls;
