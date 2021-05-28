const path = require('path');
let mode = 'development';

// Module Start

module.exports = {
  mode: mode,

  devtool: false, //compile as for reading purpose
  
  // devtool: 'eval-cheap-source-map',

  // Entry
  entry: {
    path: path.resolve(__dirname , 'src/js/index.js')
  },
  // output
  output: {
    filename: "index.js",
    path: path.resolve(__dirname, 'dist'),
  },

  // Start Module Object
  module: {
    rules: [
    // Setup javaScript
      {
        test: /\.js?$/,
        exclude: /node_modules/,
        use:{
          loader: 'babel-loader',
          }
      },
    ]
    
  },
  // End Module

};