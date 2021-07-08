const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");

/**
 *
 */
module.exports = {
  mode: "production",
  optimization: {
    minimizer: [new TerserPlugin({}), new OptimizeCssAssetsPlugin({})],
  },
  /**
   * Where to start.
   */
  entry: {
    "filters.editor": "./filters/index.js",
    "blocks.editor": "./blocks/index.js",
  },
  output: {
    filename: "./assets/js/[name].min.js",
    path: path.resolve(__dirname),
  },
  externals: {
    lodash: {
      commonjs: "lodash",
      amd: "lodash",
      root: "_", // indicates global variable.
    },
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "./assets/css/[name].css",
    }),
  ],
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              hmr: process.env.NODE_ENV === "production",
            },
          },
          "css-loader",
        ],
      },
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: "babel-loader",
        },
      },
    ],
  },
};
