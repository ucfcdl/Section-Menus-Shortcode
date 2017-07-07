var gulp = require('gulp'),
    configLocal = require('./gulp-config.json'),
    merge = require('merge'),
    eslint = require('gulp-eslint'),
    isFixed = require('gulp-eslint-if-fixed'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    scsslint = require('gulp-scss-lint'),
    autoprefixer = require('gulp-autoprefixer'),
    cleanCSS = require('gulp-clean-css'),
    include = require('gulp-include'),
    babel = require('gulp-babel'),
    uglify = require('gulp-uglify'),
    readme = require('gulp-readme-to-markdown'),
    browserSync = require('browser-sync').create();

var configDefault = {
  src: {
    js: './src/js',
    scss: './src/scss'
  },
  dist: {
    js: './static/js',
    css: './static/css'
  }
},
  config = merge(configDefault, configLocal);

// Lint all scss files
gulp.task('scss-lint', function () {
  gulp.src(config.src.scss + '/*.scss')
    .pipe(scsslint());
});

// Compile + bless primary scss files
gulp.task('css-main', function () {
  gulp.src(config.src.scss + '/ucf-news.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: ['last 2 versions', 'ie >= 9'],
      cascade: false
    }))
    .pipe(cleanCSS({ compatibility: 'ie9' }))
    .pipe(rename('ucf-news.min.css'))
    .pipe(gulp.dest(config.dist.css))
    .pipe(browserSync.stream());
});

gulp.task('es-lint', function () {
  return gulp.src(config.src.js + '/**/*.js')
    .pipe(eslint({ fix: true }))
    .pipe(eslint.format())
    .pipe(isFixed(config.src.js));
});

gulp.task('js-main', function () {
  return gulp.src(config.src.js + '/section-menu.js')
    .pipe(include())
    .on('error', console.log)
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename('section-menu.min.js'))
    .pipe(gulp.dest(config.dist.js));
});

gulp.task('js', ['es-lint', 'js-main']);

gulp.task('readme', function () {
  gulp.src('readme.txt')
    .pipe(readme({
      details: false,
      screenshot_ext: []
    }))
    .pipe(gulp.dest('.'));
});

// All css-related tasks
gulp.task('css', ['scss-lint', 'css-main']);

gulp.task('watch', function () {
  if (config.sync) {
    browserSync.init({
      proxy: {
        target: config.target
      }
    });
  }

  gulp.watch(config.src.scss + '/**/*.scss', ['css']);
  gulp.watch(config.src.js + '/*.js', ['js']).on('change', browserSync.reload);
  gulp.watch('./**/*.php').on('change', browserSync.reload);
  gulp.watch('readme.txt', ['readme']);
});

gulp.task('default', ['readme']);
