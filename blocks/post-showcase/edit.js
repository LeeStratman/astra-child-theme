/**
 * Edit function for Post Showcase Block.
 *
 * TODO: Renders 14 times on load.
 */

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { withSelect } = wp.data;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, FormTokenField } = wp.components;
const apiFetch = wp.apiFetch;
const { addQueryArgs } = wp.url;

/**
 * Post Showcase Edit function component.
 */
function PostShowcaseEdit(props) {
  const { attributes, setAttributes, postTypes, postObjects } = props;
  const { postType, selectedPosts } = attributes;

  const [postList, setPostList] = React.useState([]);

  React.useEffect(() => {
    const fetchRequest = apiFetch({
      path: addQueryArgs(`/wp/v2/${postType}`, { per_page: -1 }),
    })
      .then((postList) => {
        setPostList(postList);
      })
      .catch(() => {
        setPostList(postList);
      });
  }, [postType]);

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

  function clearPosts() {
    setAttributes({ selectedPosts: [] });
  }

  const postTypeOptions = dropDownOptions(postTypes, "name", "rest_base");

  const postSuggestions = postList.reduce(
    (accumulator, post) => [...accumulator, post.title.rendered],
    []
  );

  const selectPosts = (tokens) => {
    if (tokens.length > 4) {
      return;
    }

    const hasNoSuggestion = tokens.some(
      (token) =>
        typeof token === "string" && postSuggestions.indexOf(token) === -1
    );

    if (hasNoSuggestion) {
      return;
    }

    const allPosts = tokens.map((token) => {
      let found = token;

      postList.forEach((post) => {
        if (post.title.rendered === token) {
          found = {
            value: post.title.rendered,
            id: post.id,
          };
        }
      });

      return found;
    });

    if (allPosts.includes(null)) {
      return false;
    }

    setAttributes({ selectedPosts: allPosts });
  };

  const selectValues = selectPosts
    ? selectedPosts.reduce(
        (accumulator, post) => [...accumulator, post.value],
        []
      )
    : [];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("General", "astra-child")}>
          <SelectControl
            label={__("Post Type", "astra-child")}
            value={postType}
            options={postTypeOptions}
            help={__("Choose a post type.", "astra-child")}
            onChange={(postType) => {
              setAttributes({ postType });
              clearPosts();
            }}
          />
          <FormTokenField
            suggestions={postSuggestions}
            onChange={selectPosts}
            value={selectValues}
          />
        </PanelBody>
      </InspectorControls>
      <div className="wp-block__astra-child-post-showcase">
        {postObjects.map((post, index) => {
          return (
            <div key={index} className="post-content">
              <div className="post-content__post-thumb">
                <img src={post.img ? post.img : ""} />
              </div>
              <div className="post-content__text">
                <h3 className="post-content__title">{post.title.rendered}</h3>
                <p className="post-content__excerpt">{post.excerpt.raw}</p>
              </div>
            </div>
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
  const { selectedPosts, postType } = props.attributes;
  const { getEntityRecords, getPostTypes, getMedia } = select("core");

  const postTypes = getPostTypes();

  const postIDs = selectedPosts ? selectedPosts.map((post) => post.id) : [];

  // Retrieve case studies as an array of objects.
  const postObjects = postIDs
    ? getEntityRecords("postType", postType, {
        per_page: -1,
        include: postIDs,
      })
    : [];

  return {
    postTypes,
    postObjects: !Array.isArray(postObjects)
      ? []
      : postObjects.map((post) => {
          if (!post.featured_media) return post;

          const image = getMedia(post.featured_media);

          let imageURL = null;

          if (image) {
            imageURL = image.source_url;
          }
          return { ...post, img: imageURL };
        }),
  };
})(PostShowcaseEdit);
