const browserSync   = require('browser-sync').create();
const gulp = require('gulp');

const project_url = 'http://localhost';

module.exports = {

    browserSyncInit(done) {
        browserSync.init({
            proxy: project_url,
            notify: false
        });
        done();
    },

    browserWatch() {

        // Php and JS files (need reload)
        gulp.watch([
            './**/*.php',
            './*.php',
            './assets/js/main.min.js'
        ]).on('change', browserSync.reload);

        // CSS Files (no reload needed)
        gulp.watch([
            './assets/css/app.css'
        ]).on('change', function () {
            gulp.src('./assets/css/app.css')
                .pipe(browserSync.stream());
        });

    }

}
