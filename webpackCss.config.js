const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');
let mode = 'development';

// Module Start

module.exports = {
  mode: mode,

  devtool: false, //compile as for reading purpose
  
  // devtool: 'eval-cheap-source-map',

  // Entry
  entry: {
    path: path.resolve(__dirname , 'src/scss/style.scss')
  },
  // output
  output: {
    path: path.resolve(__dirname, 'dist'),
  },

// Plugins
plugins: [
new MiniCssExtractPlugin({ filename: "style.css"}),
],

  // Start Module Object
  module: {
    rules: [
        {
          test: /\.scss$/i,
          use:[
          MiniCssExtractPlugin.loader,
          'css-loader',
          'postcss-loader',
          'sass-loader'
          ]
        },
    ]
    
  },
  // End Module

};