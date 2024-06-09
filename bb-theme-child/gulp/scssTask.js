const sass          = require('gulp-sass')(require('sass'));
const sources  = require( "./sources.js");
const postcss = require( "gulp-postcss");
const autoprefixer = require( "autoprefixer");
const gulp = require( "gulp");

const browserCacheBust = require( "./browserCacheTask");

// sass.compiler       = require('dart-sass');

module.exports = function scssTask() {

    browserCacheBust();

    return gulp.src(sources.css.sources, {sourcemaps: true})
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(postcss([autoprefixer()]))
        .pipe(gulp.dest(sources.css.destination));

}