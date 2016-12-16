var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    // jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    // livereload = require('gulp-livereload'),
    del = require('del');

var directories = {
    assets: {
        js: 'assets',
    },
    build: {
        js: 'build',
    },
    dist: {
        js: 'dist',
    },
    public: {
        js: 'public',
    },
    root: {
        js: 'js',
    },
    resources: {
        js: 'src'
    }
}

var _name = 'jquery.slugger.js';

/*
| # Scripts
|
| The js files to be concatinated
| and saved to different folders.
|
| @run  gulp scripts
|
*/
gulp.task('scripts', function () {
    return gulp.src(directories.resources.js + '/*.js')
        .pipe(concat(_name))
        .pipe(gulp.dest(directories.dist.js))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        // .pipe(gulp.dest(directories.js.build))
        .pipe(gulp.dest(directories.dist.js))
        .pipe(notify({ message: 'Completed compiling JS Files' }));
});


/*
| # Clean
|
| @run  gulp clean
*/
gulp.task('clean', function () {
    return del([directories.dist.js]);
});

/*
| # Default Task
|
| @run  gulp default
*/
gulp.task('default', ['clean'], function () {
    gulp.start('scripts');
});
