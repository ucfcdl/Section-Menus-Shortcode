var gulp = require('gulp'),
  configLocal = require('./gulp-config.json'),
  merge = require('merge'),
  eslint = require('gulp-eslint'),
  isFixed = require('gulp-eslint-if-fixed'),
  rename = require('gulp-rename'),
  include = require('gulp-include'),
  babel = require('gulp-babel'),
  uglify = require('gulp-uglify'),
  sass = require('gulp-sass'),
  scsslint = require('gulp-scss-lint'),
  autoprefixer = require('gulp-autoprefixer'),
  cleanCSS = require('gulp-clean-css'),
  readme = require('gulp-readme-to-markdown'),
  browserSync = require('browser-sync').create();

var configDefault = {
      src: {
        scssPath: './src/scss',
        js: './src/js'
      },
      dist: {
        cssPath: './static/css',
        js: './static/js'
      }
    },
    config = merge(configDefault, configLocal);

//
// CSS
//

// Lint all scss files
gulp.task('scss-lint', function() {
  return gulp.src(config.src.scssPath + '/*.scss')
    .pipe(scsslint());
});

// Compile primary scss files
gulp.task('css-main', function() {
  return gulp.src(config.src.scssPath + '/section-menu.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(rename('section-menu.min.css'))
    .pipe(gulp.dest(config.dist.cssPath))
    .pipe(browserSync.stream());
});

// All css-related tasks
gulp.task('css', ['scss-lint', 'css-main']);

//
// JS
//

gulp.task('es-lint', function() {
  return gulp.src(config.src.js + '/**/*.js')
    .pipe(eslint({fix:true}))
    .pipe(eslint.format())
    .pipe(isFixed(config.src.js));
});

gulp.task('js-main', function() {
  return gulp.src(config.src.js + '/section-menu.js')
    .pipe(include())
      .on('error', console.log)
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename('section-menu.min.js'))
    .pipe(gulp.dest(config.dist.js));
});

gulp.task('js', ['es-lint', 'js-main']);

//
// Readme
//

gulp.task('readme', function() {
  gulp.src('readme.txt')
    .pipe(readme({
      details: false,
      screenshot_ext: []
    }))
    .pipe(gulp.dest('.'));
});

gulp.task('watch', function () {
  if (config.sync) {
    browserSync.init({
        proxy: {
          target: config.target
        }
    });
  }

  gulp.watch(config.src.scssPath + '/**/*.scss', ['css']);
  gulp.watch(config.src.js + '/**/*.js', ['js']).on('change', browserSync.reload);
  gulp.watch('./**/*.php').on('change', browserSync.reload);
});

gulp.task('default', ['readme']);
