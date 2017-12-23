/**
 * Gulp Tasks
 *
 * @since 1.0.0
 * @package indira
 */

/**
 * SET BROSWERSYNC URL
 */
var bs_url = "http://indira-active.test/";

/**
 * Grab gulp packages
 */
var gulp  = require('gulp'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    cssnano = require('gulp-cssnano'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    jshint = require('gulp-jshint'),
    stylish = require('jshint-stylish'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    plumber = require('gulp-plumber'),
    bower = require('gulp-bower'),
    babel = require('gulp-babel'),
    browserSync = require('browser-sync').create();


/**
 * Compile, autoprefix, and minify SASS
 */
gulp.task('styles', function() {
    return gulp.src('./assets/scss/**/*.scss')
        .pipe(plumber(function(error) {
            gutil.log(gutil.colors.red(error.message));
            this.emit('end');
        }))
        .pipe(sourcemaps.init()) // Start Sourcemaps
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
        .pipe(gulp.dest('./assets/css/'))
        .pipe(rename({suffix: '.min'}))
        .pipe(cssnano())
        .pipe(sourcemaps.write('.')) // Creates sourcemaps for minified styles
        .pipe(gulp.dest('./assets/css/'))
});


/**
 * JShint, concat, and minify head JS
 */
gulp.task('site-js-head', function() {

  return gulp.src([
    './assets/js/head/*.js'
  ])
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(concat('head.js'))
    .pipe(gulp.dest('./assets/js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(sourcemaps.write('.')) // Creates sourcemap for minified JS
    .pipe(gulp.dest('./assets/js'))
});


/**
 * JShint, concat, and minify foot JS
 */
gulp.task('site-js-foot', function() {

  return gulp.src([
    './assets/js/foot/*.js'
  ])
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(concat('foot.js'))
    .pipe(gulp.dest('./assets/js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(sourcemaps.write('.')) // Creates sourcemap for minified JS
    .pipe(gulp.dest('./assets/js'))
});


/**
 * BrowserSync Config
 */
gulp.task('browsersync', function() {

    // Watch files
    var files = [
    	'./assets/css/*.css',
    	'./assets/js/*.js',
    	'**/*.php',
    	'assets/images/**/*.{png,jpg,gif,svg,webp}'
    ];

    browserSync.init(files, {
	    proxy: bs_url,
		  open: false,
    });

    gulp.watch('./assets/scss/**/*.scss', ['styles']);
    gulp.watch('./assets/js/head/*.js', ['site-js-head']).on('change', browserSync.reload);
    gulp.watch('./assets/js/foot/*.js', ['site-js-foot']).on('change', browserSync.reload);
});


/**
 * Non-Browsersync JS/SASS watch
 */
gulp.task('watch', function() {

  // Watch .scss files
  gulp.watch('./assets/scss/**/*.scss', ['styles']);

  // Watch js files
  gulp.watch('./assets/js/head/*.js', ['site-js-head']);
  gulp.watch('./assets/js/foot/*.js', ['site-js-foot']);
});


/**
 * Default: Run style and js tasks
 */
gulp.task('default', function() {
  gulp.start('styles', 'site-js-head', 'site-js-foot');
});