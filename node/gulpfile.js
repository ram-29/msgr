const gulp = require('gulp');
const livereload = require('gulp-livereload');
const open = require('gulp-open');
const minify = require('gulp-minify');
const cleanCss = require('gulp-clean-css');
const rename = require('gulp-rename');

const css = _ => gulp.src(['../frontend/web/css/app/*.css'])
    .pipe(cleanCss({ level: { 1: { specialComments: 0 } } }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('../frontend/web/css'))
    .pipe(livereload({ start: true }))

const js = _ => gulp.src('../frontend/web/js/app/*.js')
    .pipe(minify({ ext: { min: '.min.js' }, noSource: true, }))
    .pipe(gulp.dest('../frontend/web/js'))
    .pipe(livereload({ start: true }))

const views = _ => gulp.src('../frontend/views/**/*.php')
    .pipe(livereload({ start: true }))

const controllers = _ => gulp.src('../frontend/controllers/*.php')
    .pipe(livereload({ start: true }))

const uri = _ => gulp.src(__filename)
    .pipe(open({ uri: 'http://fr.msgr.io/msgr' }))

function watch(done) {
    livereload.listen()

    gulp.watch(['../frontend/web/css/app/*.css'], css)
	gulp.watch(['../frontend/web/js/app/*.js'], js)

	gulp.watch(['../frontend/views/**/*.php'], views)
    gulp.watch(['../frontend/controllers/*.php'], controllers)

    done()
}

gulp.task('default', gulp.series(watch, uri))