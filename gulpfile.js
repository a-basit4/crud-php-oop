// Initialize modules 
const {src, dest, watch, series, parallel } = require('gulp');
const autoprefixer = require('autoprefixer');
const replace = require('gulp-replace');
const browserSync = require('browser-sync').create();
const settings = require('./settings');
const webpack = require('webpack');

// File path variables
const jsSrc = 'src/js/';

const files = {
  scssPath : 'src/scss/**/*.scss',
  jsPath: 'src/js/**/*.js',
  phpPath: './**/*.php'
}

// Sass Task
function scssTask(scss) {
   webpack(require('./webpackCss.config.js'), function(err, stats) {
      if (err) {
        console.log(err.toString());
      }
      console.log(stats.toString());
    });
    scss();
}

// Js Task
function jsTask(callback) {
   webpack(require('./webpack.config.js'), function(err, stats) {
      if (err) {
        console.log(err.toString());
      }
      console.log(stats.toString());
    });
    callback();
}
// Cache Busting Task
const cbString = new Date().getTime();
function cacheBusting() {
  return src(['index.php'])
  .pipe(replace(/cb=\d+/g , 'cb=' + cbString))
  .pipe(dest('.'));
}

// Watch function
function watchTask() {
  browserSync.init({
    notify: false,
    proxy: settings.urlToPreview,
    ghostMode: false
  });
  watch(files.scssPath , scssTask);
  watch(files.jsPath , jsTask);
  watch(files.jsPath).on('change', browserSync.reload);
  watch(files.phpPath).on('change', browserSync.reload);
  watch(files.scssPath).on('change', browserSync.reload);
}

exports.scss = scssTask;
exports.js = jsTask;
exports.default = series(
  parallel(scssTask , jsTask),
  cacheBusting,
  watchTask
  );