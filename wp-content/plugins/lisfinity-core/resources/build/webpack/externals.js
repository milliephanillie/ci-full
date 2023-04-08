const nodeExternals = require('webpack-node-externals');
module.exports = {
  jquery: 'jQuery',
  target: 'node', // in order to ignore built-in modules like path, fs, etc.
};
