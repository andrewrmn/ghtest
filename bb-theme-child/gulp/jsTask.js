const gulp = require( "gulp");
const plumber = require( "gulp-plumber");
const babel = require( "gulp-babel");
const concat = require( "gulp-concat");
const minifyJS = require( "gulp-minify");
const sources = require( "./sources");
const browserCacheBust = require( "./browserCacheTask");

/**
 * Main JS Task.
 * Concats all the files in /assets/src/js into /assets/js/main.js in minified form
 */
module.exports = function jsTask() {

    browserCacheBust();

    return gulp.src(sources.js.sources, {sourcemaps: true})
        .pipe(plumber())
        .pipe(babel())
        .pipe(concat(sources.js.name))
        .pipe(gulp.dest(sources.js.destination))
        .pipe(minifyJS({
            ext : '.min.js'
        }))
        .pipe(gulp.dest('./assets/js'));
}