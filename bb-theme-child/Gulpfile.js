'use strict';

const gulp = require('gulp');


const jsTask = require('./gulp/jsTask');
const scssTask = require('./gulp/scssTask');
const moduleTask = require('./gulp/scssModuleTask');
const { browserWatch, browserSyncInit } = require( "./gulp/browserSync");


/*
Task definition
 */


gulp.task('watch', function () {
    gulp.watch(['./src/scss/**/*.scss'], scssTask);
    gulp.watch(['./src/js/**/*.js'], jsTask );
});

gulp.task('js', jsTask );
gulp.task('scss', scssTask );
gulp.task('browser', gulp.parallel(browserSyncInit, browserWatch));
gulp.task('module', moduleTask );

gulp.task('default', gulp.series([
    'scss',
    'js',
    gulp.parallel(['watch', 'browser'])
]));