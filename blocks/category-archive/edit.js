/**
 * Edit function for Category Archive Block.
 */

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { withSelect } = wp.data;
const { InspectorControls, URLInput, MediaPlaceholder } = wp.blockEditor;
const { PanelBody, SelectControl, TextControl, ToggleControl } = wp.components;

/**
 * Module constants.
 */
const ALLOWED_MEDIA_TYPES = ["image"];

/**
 * Showcase Edit function component.
 */
function CategoryArchiveEdit(props) {
  const { attributes, setAttributes, taxonomies, terms } = props;
  const { selectedTaxonomy, termData, showEmpty } = attributes;

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

  const taxonomyOptions = dropDownOptions(taxonomies, "name", "slug");

  /**
   *
   * @param {string} text The text to display.
   * @param {number} numWords The number of words to display.
   */
  function displayText(text, numWords) {
    numWords = Number(numWords);
    if (text.length < numWords || "number" !== typeof numWords) {
      return text;
    }

    const textArray = text.split(" ");

    if (textArray.length < numWords) {
      return text;
    }

    let textString = "";
    for (let index = 0; index < numWords; index++) {
      textString = textString + textArray[index];

      if (index < numWords - 1) {
        textString = textString + " ";
      }
    }

    return textString + "...";
  }
  return (
    <>
      <InspectorControls>
        <PanelBody title={__("General", "astra-child")}>
          <SelectControl
            label={__("Taxonomy", "astra-child")}
            value={selectedTaxonomy}
            options={taxonomyOptions}
            help={__("Choose a taxonomy.", "astra-child")}
            onChange={(selectedTaxonomy) => setAttributes({ selectedTaxonomy })}
          />
          <ToggleControl
            label={__("Show Empty Categories?", "astra-child")}
            checked={showEmpty}
            onChange={() => setAttributes({ showEmpty: !showEmpty })}
          />
        </PanelBody>
        <PanelBody title={__("Terms", "astra-child")}>
          {terms &&
            terms.map((term, index) => {
              return <li key={index}>{term.name}</li>;
            })}
        </PanelBody>
      </InspectorControls>
      <div className="wp-block__astra-child-category-archive">
        {terms &&
          terms.map((term, index) => {
            return (
              <article
                key={index}
                className="ast-article-post astra-child__single-taxonomy-article"
              >
                <div className="astra-child__single-taxonomy-layout">
                  <div className="astra-child__single-taxonomy-content">
                    <header className="astra-child__single-taxonomy-header">
                      <h2 className="astra-child__single-taxonomy-title entry-title">
                        {term.name}
                      </h2>
                    </header>
                    <div className="astra-child__single-taxonomy-entry-content">
                      <p className="astra-child__single-taxonomy-content-text">
                        {displayText(term.description, 31)}
                      </p>
                      <p className="read-more">
                        <a className="ast-button" href="#">{`Learn More`}</a>
                      </p>
                    </div>
                  </div>
                  <div className="astra-child__single-taxonomy-thumb">
                    {term.img && <img src={term.img} />}
                  </div>
                </div>
              </article>
            );
          })}
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
  const { selectedTaxonomy } = props.attributes;

  // Get available taxonomies.
  const taxonomies = select("core").getTaxonomies();

  // Get available terms from selected taxonomies.
  const terms = select("core").getEntityRecords("taxonomy", selectedTaxonomy);

  return {
    taxonomies,
    terms: !Array.isArray(terms)
      ? []
      : terms.map((term) => {
          if (!term.featured_image || 0 === term.featured_image) return term;

          const image = select("core").getMedia(term.featured_image);

          let imageURL = null;

          if (image) {
            imageURL = image.source_url;
          }

          return { ...term, img: imageURL };
        }),
  };
})(CategoryArchiveEdit);
