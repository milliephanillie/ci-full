
/**
 * The internal dependencies.
 */
const utils = require('./lib/utils');

/**
 * External dependencies.
 */
const { env: envName } = utils.detectEnv();
const purgecss = require('@fullhuman/postcss-purgecss')({

  // Specify the paths to all of the template files in your project
  content: [
    utils.lisfinityPath('./index.php'),
    utils.lisfinityPath('./header.php'),
    utils.lisfinityPath('./footer.php'),
    utils.lisfinityPath('./page.php'),
    utils.lisfinityPath('./single.php'),
    utils.lisfinityPath('./sidebar.php'),
    utils.lisfinityPath('./404.php'),
    utils.lisfinityPath('./archive.php'),
    utils.lisfinityPath('./comments.php'),
    utils.lisfinityPath('./includes/**/*.php'),
    utils.lisfinityPath('./templates/**/*.php'),
    utils.themeRootPath('./templates/**/*.php'),
    utils.themeRootPath('./resources/scripts/**/*.js'),
    utils.themeRootPath('./resources/scripts/**/*.jsx'),
    utils.themeRootPath('./resources/styles/**/*.scss'),
    // etc.
  ],

  // Include any special characters you're using in this regular expression
  extractors: [
    {
      extractor: class TailwindExtractor {
        static extract(content) {
          return content.match(/[A-Za-z0-9-_:\/]+/g) || [];
        }
      },
      extensions: ['php', 'js', 'svg', 'scss', 'jsx']
    }
  ],
  whitelist: [
    'html',
    'body',
    'lg:w-25%',
    'lg:w-50%',
    'lg:w-75%',
    'sm:w-48%',
    'sm:px-0',
    'bg:w-47%',
    'bg:w-48%',
    'bg:w-96%',
    'bg:ml-20',
  ],
  whitelistPatterns: [
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
    /^gu(-.*)?$/,
    /^w(-.*)?$/,
    /^xl:w(-.*)?$/,
    /^text(-.*)?$/,
    /^lisfinity-order(-.*)?$/,
  ],
});

/**
 * Setup PostCSS plugins.
 */
const plugins = [
  require('tailwindcss')(utils.srcPath('build/tailwind.js')),
  require('autoprefixer'),
  ...process.env.NODE_ENV === 'production'
    ? [purgecss] // [purgecss] to include it
    : [],
];

/**
 * Prepare the configuration.
 */
const config = {
  plugins,
  options: {
    ident: 'postcss',
  },
};

module.exports = config;
