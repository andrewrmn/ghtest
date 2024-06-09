module.exports = {
    js : {
        sources : [
            './src/js/deps/*.js',
            './src/js/*.js',
        ],
        name : 'main.js',
        destination : './assets/js'
    },

    css : {
        sources: './src/scss/**/*.scss',
        destination: './assets/css',
        modules_dir : './src/scss/modules/',
        modules_index : './src/scss/_modules-index.scss'
    }
}