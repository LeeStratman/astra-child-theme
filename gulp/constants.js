"use-strict";

// Root path is where npm run commands happen
export const rootPath = process.cwd();

export const gulpPath = `${rootPath}/gulp`;

// Dev or production
export const isProd = process.env.NODE_ENV === "production";

// directory for assets (CSS, JS, images)
export const assetsDir = `${rootPath}/assets`;

// Project paths
const paths = {
  assetsDir,
  styles: {
    editorSrc: [
      `${assetsDir}/css/src/editor/**/*.css`,
      // Ignore partial files.
      `!${assetsDir}/css/src/**/_*.css`,
    ],
    editorSrcDir: `${assetsDir}/css/src/editor`,
    editorDest: `${assetsDir}/css/editor`,
    src: [
      `${assetsDir}/css/src/**/*.css`,
      // Ignore partial files.
      `!${assetsDir}/css/src/**/_*.css`,
      // Ignore editor source css.
      `!${assetsDir}/css/src/editor/**/*.css`,
    ],
    srcDir: `${assetsDir}/css/src`,
    dest: `${assetsDir}/css`,
  },
  scripts: {
    src: [
      `${assetsDir}/js/src/**/*.js`,
      // Ignore partial files.
      `!${assetsDir}/js/src/**/_*.js`,
    ],
    srcDir: `${assetsDir}/js/src`,
    dest: `${assetsDir}/js`,
  },
  export: {
    src: [],
    stringReplaceSrc: [`${rootPath}/style.css`, `${rootPath}/languages/*.po`],
  },
};

export { paths };
