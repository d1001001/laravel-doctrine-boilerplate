var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var cssImport = require('gulp-import-css');
var streamqueue = require('streamqueue');

var STYLES_FRONT = 'app/Frontend/assets/style/main.scss';
var STYLES_BACK = 'app/Backend/assets/style/main.scss';
var JAVASCRIPTS = 'app/assets/js/**/*.js';

gulp.task('styles-front', function () {
    return gulp.src( STYLES_FRONT )
        .pipe(sass())
        .pipe(concat('style.min.css'))
        .pipe(gulp.dest('public/'));
});
gulp.task('styles-back', function () {
    return gulp.src( STYLES_BACK )
        .pipe(sass())
        .pipe(concat('style.min.css'))
        .pipe(cssImport())
        .pipe(gulp.dest('public/'));

    //var vendorFiles = gulp.src('app/Backend/assets/style/semantic.min.css');
    //var appFiles = gulp.src(STYLES_BACK)
    //    .pipe(sass());
    //return es.concat(appFiles, vendorFiles)
    //    .pipe(concat('style.min.css'))
    //    .pipe(gulp.dest('public/'));
});

gulp.task('scripts', function(){
    return streamqueue({ objectMode: true },
        gulp.src('app/assets/js/app.js')
    )
        .pipe(concat('js.min.js'))
        .pipe(gulp.dest('public'))
        //.pipe(uglify())
        .pipe(gulp.dest('public/'));
});

gulp.task('default', function () {
});

// Watch
gulp.task('watch', function() {
    // Watch .scss files
    gulp.watch(STYLES_BACK, ['styles-back']);
    // Watch .js files
    gulp.watch(JAVASCRIPTS, ['scripts']);
    // Watch image files
    //gulp.watch('src/images/**/*', ['images']);
    // Create LiveReload server
    //livereload.listen();
    // Watch any files in dist/, reload on change
    //gulp.watch(['dist/**']).on('change', livereload.changed);
});