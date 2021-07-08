"use-strict";

import { src, dest } from "gulp";
import postcss from "gulp-postcss";
import cssnano from "cssnano";

/**
 * Internal dependencies
 */
import { paths, isProd } from "./constants";
import autoprefixer from "autoprefixer";

/**
 * CSS
 * @param {function} done function to call when async processes finish
 */
export default function styles(done) {
  var cssPlugins = [
    autoprefixer({
      stage: 3,
      autoprefixer: {
        grid: false,
      },
      features: {
        "custom-media-queries": true,
        "custom-properties": true,
        "nesting-rules": true,
      },
    }),
    cssnano(),
  ];
  return src(paths.styles.src, { sourcemaps: !isProd })
    .pipe(postcss(cssPlugins))
    .pipe(
      dest(paths.styles.dest, {
        sourcemaps: !isProd,
      })
    );
}
