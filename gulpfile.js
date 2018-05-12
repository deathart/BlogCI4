const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('autoprefixer');
const plumber = require("gulp-plumber");
const cleanCSS = require('gulp-clean-css');
const clean = require('gulp-clean');
const uglify = require('gulp-uglify');
const gulpSequence = require('gulp-sequence');
const path = require('path');
const rev = require('gulp-rev');
const revdel = require('gulp-rev-delete-original');
const postcss = require('gulp-postcss');
const image = require('gulp-image');
const override=require('gulp-rev-css-url');

const config = {
    entry: path.join(__dirname, '/resources/assets/'),
    output: path.join(__dirname, '/public/assets/'),
    sassoptions: {
        errLogToConsole: true,
        outputStyle: 'expanded'
    }
};

gulp.task('scss', function(){
    return gulp.src(config.entry + "/scss/**/*.scss")
        .pipe(plumber())
        .pipe(sass(config.sassoptions).on('error', sass.logError))
        .pipe(postcss([autoprefixer({browsers: ['last 1 version']})]))
        .pipe(gulp.dest(config.output + "/css"));
});

gulp.task('js', function(){
    return gulp.src(config.entry + "/js/**/*.js")
        .pipe(plumber())
        .pipe(gulp.dest(config.output + "/js"));
});

gulp.task('image', function () {
    gulp.src(config.entry + "/images/**/*.*")
        .pipe(image())
        .pipe(gulp.dest(config.output + "/images"));
});

gulp.task('fonts', function() {
    return gulp.src(config.entry + "/fonts/**/*.*")
        .pipe(gulp.dest(config.output + "/fonts"));
});

gulp.task('cleancss', function(){
    return gulp.src(config.output + "\\css\\**\\*.css")
        .pipe(cleanCSS())
        .pipe(gulp.dest(config.output + "/css"));
});

gulp.task('cleanjs', function(){

    return gulp.src(config.output + "/js/**/*.js")
        .pipe(plumber())
        .pipe(uglify())
        .pipe(gulp.dest(config.output + "/js"));
});

gulp.task('clean', function() {
    return gulp.src([
        config.output + "/css/**/*.css",
        config.output + "/js/**/*.js",
        config.output + "/images/**/*.*",
        config.output + "/rev-manifest.json"
    ]).pipe(clean({force: true}));
});

gulp.task('revisioning', function() {
    return gulp.src(config.output + "/**")
        .pipe(gulp.dest(config.output))
        .pipe(rev())
        .pipe(override())
        .pipe(gulp.dest(config.output))
        .pipe(revdel())
        .pipe(rev.manifest())
        .pipe(gulp.dest(config.output));
});

gulp.task('watch', function(){
    gulp.watch(config.entry + "/scss/**/*.scss").on('change', function(path) {
        console.log('File ' + path.path + ' was changed');
        gulpSequence('clean', ['scss', 'js'], ['cleancss', 'cleanjs'], 'revisioning')(function (err) {
            if (err) console.log(err)
        })
    });

    gulp.watch(config.entry + "/js/**/*.js").on('change', function(path) {
        console.log('File ' + path.path + ' was changed');
        gulpSequence('clean', ['scss', 'js'], ['cleancss', 'cleanjs'], 'revisioning')(function (err) {
            if (err) console.log(err)
        })
    });
});

gulp.task('build', gulpSequence('clean', ['scss', 'js', 'image', 'fonts'], ['cleancss', 'cleanjs'], 'revisioning'));
gulp.task('default', ['scss', 'js' ]);
