/**
 * The external dependencies.
 */
const { ProvidePlugin, WatchIgnorePlugin } = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const RtlCssPlugin = require('rtlcss-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const WebpackBuildNotifierPlugin = require('webpack-build-notifier');

/**
 * The internal dependencies.
 */
const utils = require('./lib/utils');
const configLoader = require('./config-loader');
const spriteSmith = require('./spritesmith');
const postcss = require('./postcss');

/**
 * Setup the env.
 */
const { env: envName } = utils.detectEnv();

/**
 * Setup babel loader.
 */
const babelLoader = {
  loader: 'babel-loader',
  options: {
    cacheDirectory: false,
    comments: false,
    presets: [
      ['@babel/preset-env', {
        modules: false,
        targets: { node: 6 },
      }],
      ['@babel/preset-react', {
        pragma: 'wp.element.createElement',
        pragmaFrag: 'wp.element.Fragment',
      }],
      [
        'env',
        {
          targets: {
            browsers: ['last 3 versions'],
          },
        },
      ],
      // airbnb not included as stage-2 already covers it
      'stage-2',
      'react',
    ],
    plugins: [
      'lodash',
      '@babel/plugin-proposal-class-properties',
      '@babel/plugin-proposal-object-rest-spread',
      '@babel/plugin-syntax-async-generators',
      '@babel/plugin-transform-runtime',
    ],
  },
};

/**
 * Setup extract text plugin.
 */
const extractSass = new MiniCSSExtractPlugin({
  filename: 'styles/[name].css',
});

/**
 * Setup webpack plugins.
 */
const webpack = require('webpack');
const plugins = [
  new CleanWebpackPlugin(utils.distPath(), {
    root: utils.themeRootPath(),
  }),
  new WatchIgnorePlugin([
    utils.distImagesPath('sprite.png'),
    utils.distImagesPath('sprite@2x.png'),
  ]),
  new ProvidePlugin({
    $: 'jquery',
    jQuery: 'jquery',
    React: 'react',
  }),
  extractSass,
  new RtlCssPlugin('styles/theme-rtl.css'),
  spriteSmith,
  new TerserPlugin(),
  new ManifestPlugin(),
  new OptimizeCssAssetsPlugin({
    cssProcessorPluginOptions: {
      preset: ['default', { discardComments: { removeAll: true } }],
    },
  }),
  new CopyWebpackPlugin([
    { from: utils.srcStaticsPath(), to: utils.distStaticsPath() },
  ]),
  new WebpackBuildNotifierPlugin({
    title: 'Lisfinity Build',
    suppressWarning: true,
  }),
];

/**
 * Export the configuration.
 */
module.exports = {
  /**
   * The input.
   */
  entry: require('./webpack/entry'),

  /**
   * The output.
   */
  output: require('./webpack/output'),

  /**
   * Resolve utilities.
   */
  resolve: require('./webpack/resolve'),

  /**
   * Resolve the dependencies that are available in the global scope.
   */
  externals: require('./webpack/externals'),

  /**
   * Setup the transformations.
   */
  module: {
    rules: [
      /**
       * Add support for blogs in import statements.
       */
      {
        enforce: 'pre',
        test: /\.(js|jsx|css|scss)$/,
        use: 'import-glob',
      },

      /**
       * Handle the theme config.json.
       */
      {
        test: utils.themeRootPath('config.json'),
        use: configLoader,
      },

      /**
       * Handle scripts.
       */
      {
        test: utils.tests.scripts,
        exclude: /node_modules/,
        use: babelLoader,
      },

      /**
       * Handle styles.
       */
      {
        test: utils.tests.styles,
        use: [
          {
            loader: MiniCSSExtractPlugin.loader,
            options: {
              publicPath: '../',
            },
          },
          {
            loader: 'css-loader',
            options: {},
          },
          {
            loader: 'postcss-loader',
            options: postcss,
          },
          {
            loader: 'sass-loader',
          },
        ],
      },

      /**
       * Handle images.
       */
      {
        test: utils.tests.images,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: file => `images/[name].${utils.filehash(file).substr(0, 10)}.[ext]`,
            },
          },
        ],
      },

      /**
       * Handle fonts.
       */
      {
        test: utils.tests.fonts,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: file => `fonts/[name].${utils.filehash(file).substr(0, 10)}.[ext]`,
            },
          },
        ],
      },
    ],
  },

  /**
   * Setup the transformations.
   */
  plugins,

  optimization: {
    splitChunks: {
      chunks: 'async',
      minSize: 20000,
      maxSize: 0,
      minChunks: 1,
      maxAsyncRequests: 30,
      maxInitialRequests: 30,
      automaticNameDelimiter: '~',
      cacheGroups: {
        defaultVendors: {
          test: /[\\/]node_modules[\\/]/,
          priority: -10
        },
        default: {
          minChunks: 2,
          priority: -20,
          reuseExistingChunk: true
        }
      }
    }
  },

  /**
   * Setup the development tools.
   */
  mode: envName,
  cache: false,
  bail: false,
  watch: false,
  devtool: false,
};
