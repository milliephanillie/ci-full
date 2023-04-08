/**
 * The internal dependencies.
 */
const utils = require('../lib/utils');

module.exports = {
  'theme': utils.srcScriptsPath('theme/index.js'),
  'dashboard': utils.srcScriptsPath('dashboard/index.js'),
  'admin-products': utils.srcScriptsPath('admin/products.js'),
  'taxonomies': utils.srcScriptsPath('admin/taxonomies.js'),
  'admin-search-builder': utils.srcScriptsPath('admin/search-builder/index.js'),
  'admin': utils.srcScriptsPath('admin/index.js'),
  'payouts': utils.srcScriptsPath('admin/payouts.js'),
};
