var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    cache = require('gulp-cache'),
    del = require('del'),
    svgmin = require('gulp-svgmin');

gulp.task('styles', function() {
  return sass('src/css/main.scss', { style: 'expanded' })
    .pipe(autoprefixer())
    .pipe(gulp.dest('assets/css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(minifycss({keepSpecialComments:0}))
    .pipe(gulp.dest('assets/css'));
});

gulp.task('general-scripts', function() {
  return gulp.src(['src/js/modernizr.js', 'src/js/jquery.js', 'src/js/scripts.js', 'src/js/foundation.min.js'])
    .pipe(concat('main.js'))
    .pipe(gulp.dest('assets/js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('assets/js'))
});
      
gulp.task('datatables-scripts', function() {
  return gulp.src(['src/js/jquery.dataTables.js'])
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest('assets/js'))
});

gulp.task('scripts', ['general-scripts', 'datatables-scripts']);

gulp.task('images', function() {
  return gulp.src('src/img/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 5, progressive: true, interlaced: true })))
    .pipe(gulp.dest('assets/img'))
});

gulp.task('svg', function() {
  return gulp.src('src/svg/**/*')
    .pipe(svgmin())
    .pipe(gulp.dest('assets/svg'));
});

gulp.task('clean', function(cb) {
    del(['assets/css', 'assets/js', 'assets/img', 'assets/svg'], cb)
});

gulp.task('watch', function() {
  gulp.watch('src/css/**/*.scss', ['styles']);
  gulp.watch('src/js/**/*.js', ['scripts']);
  gulp.watch('src/img/**/*', ['images']);
  gulp.watch('src/svg/**/*', ['svg']);
});

gulp.task('build', ['styles', 'scripts', 'images', 'svg']);