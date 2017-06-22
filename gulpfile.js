var gulp        = require('gulp'),
    configLocal = require('./gulp-config.json'),
    merge       = require('merge'),
    eslint      = require('gulp-eslint'),
    isFixed     = require('gulp-eslint-if-fixed'),
    rename      = require('gulp-rename'),
    include     = require('gulp-include'),
    babel       = require('gulp-babel'),
    uglify      = require('gulp-uglify'),
    readme      = require('gulp-readme-to-markdown');

var configDefault = {
  src: {
    js: './src/js'
  },
  dist: {
    js: './static/js'
  }
},
config = merge(configDefault, configLocal);

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

gulp.task('readme', function() {
  gulp.src('readme.txt')
    .pipe(readme({
      details: false,
      screenshot_ext: []
    }))
    .pipe(gulp.dest('.'));
});

gulp.task('watch', function() {
  gulp.watch(config.src.js + '/**/*.js', ['js']);
});

gulp.task('default', ['readme']);
