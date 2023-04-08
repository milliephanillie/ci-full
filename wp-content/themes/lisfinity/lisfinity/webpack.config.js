const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const PurgeCSS = require('@fullhuman/postcss-purgecss');
const isProduction = 'production' === process.env.NODE_ENV;

// Set the build prefix.
let prefix = isProduction ? '' : '';

// Set the PostCSS Plugins.
const post_css_plugins = [
  require('postcss-import'),
  require('tailwindcss')('./tailwind.js'),
  require('postcss-nested'),
  require('postcss-custom-properties'),
  require('autoprefixer')
];

module.exports.filehash = (file) => {
  const hash = crypto.createHash('sha1');
  hash.update(fs.readFileSync(file));
  return hash.digest('hex');
};

// Add PurgeCSS for production builds.
if (isProduction) {
  post_css_plugins.push(
    PurgeCSS({
      content: [
        './**/*.php',
        './resources/css/whitelist/*.scss',
      ],
      css: [
        './node_modules/tailwindcss/dist/base.css'
      ],
      extractors: [
        {
          extractor: class TailwindExtractor {
            static extract(content) {
              return content.match(/[A-Za-z0-9-_:\/]+/g) || [];
            }
          },
          extensions: ['php', 'js', 'svg', 'css', 'scss']
        }
      ],
      whitelistPatterns: getCSSWhitelistPatterns()
    })
  );
}

const config = {
  entry: './resources/js/main.js',
  output: {
    filename: `[name]${prefix}.js`,
    path: path.resolve(__dirname, 'dist')
  },
  mode: process.env.NODE_ENV,
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(js|jsx|css|scss|sass)$/,
        use: 'import-glob',
      },
      {
        test: /\.(css|scss)$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: '../',
              hmr: true,
            },
          },
          {
            loader: 'css-loader',
            options: {
              importLoaders: 1,
              context: 'postcss',
              sourceMap: !isProduction
            }
          },
          {
            loader: 'postcss-loader',
            options: {
              ident: 'postcss',
              sourceMap: isProduction || 'inline',
              plugins: post_css_plugins,
            },
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: !isProduction
            }
          },
        ],
      },
      {
        test: /fonts[\\/].*\.(eot|svg|ttf|woff|woff2)$/, use: [
          {
            loader: 'file-loader',
            options: {
              name: file => `fonts/[name].[ext]`,
              publicPath: '',
              esModule: false,
            },
          },
        ],
      }
    ]
  },
  resolve: {
    alias: {
      '@': path.resolve('resources'),
      '@images': path.resolve('../images'),
      '@fonts': path.resolve('../fonts'),
      '@scripts': path.resolve('../scripts')
    }
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: `styles/[name]${prefix}.css`,
    }),
    new CopyWebpackPlugin([
      {
        from: './resources/scripts/',
        to: 'scripts',
        ignore: [
          '.DS_Store'
        ]
      },
    ]),
  ],
  devtool: 'source-map',
};

// Fire up a local server if requested
if (process.env.SERVER) {
  config.plugins.push(
    new BrowserSyncPlugin(
      {
        proxy: 'https://lisfinity-test.test',
        files: [
          '**/*.php',
          '**/*.scss'
        ],
        port: 3000,
        notify: false,
      }
    )
  );
}

/**
 * List of RegExp patterns for PurgeCSS
 * @returns {RegExp[]}
 */
function getCSSWhitelistPatterns() {
  return [
    /^home(-.*)?$/,
    /^blog(-.*)?$/,
    /^archive(-.*)?$/,
    /^date(-.*)?$/,
    /^error404(-.*)?$/,
    /^admin-bar(-.*)?$/,
    /^search(-.*)?$/,
    /^nav(-.*)?$/,
    /^wp(-.*)?$/,
    /^screen(-.*)?$/,
    /^navigation(-.*)?$/,
    /^(.*)-template(-.*)?$/,
    /^(.*)?-?single(-.*)?$/,
    /^postid-(.*)?$/,
    /^post-(.*)?$/,
    /^attachmentid-(.*)?$/,
    /^attachment(-.*)?$/,
    /^page(-.*)?$/,
    /^(post-type-)?archive(-.*)?$/,
    /^author(-.*)?$/,
    /^category(-.*)?$/,
    /^tag(-.*)?$/,
    /^menu(-.*)?$/,
    /^tags(-.*)?$/,
    /^tax-(.*)?$/,
    /^term-(.*)?$/,
    /^date-(.*)?$/,
    /^(.*)?-?paged(-.*)?$/,
    /^depth(-.*)?$/,
    /^children(-.*)?$/,
  ];
}

module.exports = config;
