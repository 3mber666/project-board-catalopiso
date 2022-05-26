var gulp           = require('gulp'),
    minifyCss      = require('gulp-clean-css'),
    sass           = require('gulp-sass'),
    combineMq      = require('gulp-combine-mq'),
    autoprefixer   = require('gulp-autoprefixer'),
    mainBowerFiles = require('main-bower-files'),
    addsrc         = require('gulp-add-src'),
    concat         = require('gulp-concat'),
    sourcemaps     = require('gulp-sourcemaps');
    runSequence    = require('run-sequence');


var scssSrc       = 'scss/components/*.scss';
var cssDist       = 'css/';
var jsDist        = 'js/';
var jsMagnificSrc = 'bower_components/magnific-popup/dist/jquery.magnific-popup.min.js';
var cssMagnificSrc = 'bower_components/magnific-popup/dist/magnific-popup.css';


/* Call while working with project */
gulp.task('default', ['watch']);

/* Call at first start, after changes in bower.json or in gulp.js */
gulp.task('build', function (cb) {
  runSequence('cssbuild', 'libs', 'jsbuild', 'magnific', cb);
});

/* Watch command */
gulp.task('watch', ['css'], function () {
  gulp.watch(scssSrc, ['css']);
});

/* CSS production file packaging */
gulp.task('css', function () {

  /* Bower css libraries. You can config list of files in bower.json */
  var bowerCss = mainBowerFiles('**/*.css');

  return gulp.src('scss/final.scss')
      //.pipe(sourcemaps.init())
      .pipe(sass({
        precision:    10
      }).on('error', sass.logError))
      .pipe(addsrc.append(bowerCss))
      .pipe(concat('premmerce-wishlist.css'))
      //.pipe(sourcemaps.write())
      .pipe(gulp.dest(cssDist));
});

/* CSS production file packaging */
gulp.task('cssbuild', function () {

  /* Bower css libraries. You can config list of files in bower.json */
  var bowerCss = mainBowerFiles('**/*.css');

  return gulp.src('scss/final.scss')
      .pipe(sass({
        precision:    10
      }).on('error', sass.logError))
      .pipe(addsrc.append(bowerCss))
      .pipe(concat('premmerce-wishlist.css'))
      .pipe(autoprefixer({
        browsers: ['last 4 versions', 'ie > 8', '> 1%']
      }))
      .pipe(combineMq())
      .pipe(minifyCss({"keepSpecialComments": 0}))
      .pipe(gulp.dest(cssDist));
});

gulp.task('magnificCss', function() {
  return gulp.src(jsMagnificSrc).pipe(gulp.dest(jsDist));
});

gulp.task('magnificJs', function() {
  return gulp.src(cssMagnificSrc).pipe(gulp.dest(cssDist));
});

