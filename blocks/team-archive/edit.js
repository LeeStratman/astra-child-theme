/**
 * Edit function for Team Archive Block.
 */

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { withSelect, select } = wp.data;
const { InspectorControls, URLInput, MediaPlaceholder } = wp.blockEditor;
const { PanelBody, SelectControl, TextControl, ToggleControl } = wp.components;

/**
 * Showcase Edit function component.
 */
function TeamArchiveEdit(props) {
  const { attributes, setAttributes, teamMembers, media } = props;

  return (
    <>
      <InspectorControls></InspectorControls>
      <div className="wp-block__astra-child-team-archive">
        {teamMembers &&
          teamMembers.map((member, index) => {
            return (
              <article key={index} class="ast-article-post team-member-article">
                <div className="ast-post-format- blog-layout-2 ast-no-date-box">
                  <div className="post-content ast-col-md-6">
                    <header className="entry-header">
                      <h2 className="entry-title article-title-blog">
                        {member.title.rendered}
                      </h2>
                    </header>
                    <div className="entry-content clear article-entry-content-blog-layout-2">
                      <p className="team-member-job-title">
                        {member.employee_info.job_title}
                      </p>
                      <a href={`mailto:${member.employee_info.email}`}>
                        Contact Me
                      </a>
                      <p className="read-more">
                        <a className="ast-button" href="#">{`Learn More`}</a>
                      </p>
                    </div>
                  </div>
                  {media[index] && (
                    <img
                      src={`${
                        media[index].media_details.sizes[
                          "astra-child-team-member-archive"
                        ]
                          ? media[index].media_details.sizes[
                              "astra-child-team-member-archive"
                            ].source_url
                          : media[index].media_details.sizes["full"].source_url
                      }`}
                    />
                  )}
                  <div>{`Image Here`}</div>
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
  // Get available taxonomies.
  const teamMembers = select("core").getEntityRecords(
    "postType",
    "astra_child_team",
    {
      per_page: -1,
    }
  );

  const media = teamMembers
    ? teamMembers.map((member, index) => {
        if (member.featured_media > 0) {
          console.log("featured_media", member.featured_media);
          return select("core").getMedia(member.featured_media);
        }
        return "";
      })
    : [];
  console.log(media);
  return { teamMembers, media };
})(TeamArchiveEdit);
