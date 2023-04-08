/*
 |--------------------------------------------------------------------------
 | Gulpfile Asset Management
 |--------------------------------------------------------------------------
 |
 */
'use strict';

// Load plugins
const gulp = require('gulp');
const notify = require('gulp-notify');
const zip = require('gulp-zip');
const readlineSync = require('readline-sync');

// prepare and zip required plugins
gulp.task('zip-plugins', function () {
  return gulp.src([
    './../../plugins/{lisfinity-core,lisfinity-core/**}',
    '!./../../plugins/lisfinity-core/node_modules/**',
    '!./../../plugins/lisfinity-core/resources/**',
    '!./../../plugins/lisfinity-core/vendor/bin/**',
    '!./../../plugins/lisfinity-core/vendor/bin/doctrine/**',
    '!./../../plugins/lisfinity-core/vendor/bin/filp/**',
    '!./../../plugins/lisfinity-core/vendor/bin/myclabs/**',
    '!./../../plugins/lisfinity-core/vendor/bin/phpdocumentor/**',
    '!./../../plugins/lisfinity-core/vendor/bin/phpspec/**',
    '!./../../plugins/lisfinity-core/vendor/bin/phpunit/**',
    '!./../../plugins/lisfinity-core/vendor/bin/psr/**',
    '!./../../plugins/lisfinity-core/vendor/bin/sebiastian/**',
    '!./../../plugins/lisfinity-core/vendor/bin/symfony/**',
    '!./../../plugins/lisfinity-core/vendor/bin/theseer/**',
    '!./../../plugins/lisfinity-core/vendor/bin/webmozart/**',
  ])
    .pipe(zip('lisfinity-core.zip'))
    .pipe(gulp.dest('./lib'))
    .pipe(notify({ message: 'The plugins are zipped' }));
});

// prepare theme for themeforest submission
gulp.task('zip-theme', function () {
  return gulp.src(['./../{lisfinity,lisfinity/**}',
    '!./{node_modules,node_modules/**}',
    '!./{resources,resources/**}',
    '!./{vendor,vendor/filp/**}',
    '!./{vendor,vendor/psr/**}',
    '!./{vendor,vendor/symfony/**}',
    '!./{vendor,vendor/composer/installers/**}',
  ])
    .pipe(zip('lisfinity.zip'))
    .pipe(gulp.dest('./'))
    .pipe(notify({ message: 'Theme has been zipped' }));
});
gulp.task('themeforest', gulp.series(function (done) {
    return done();
  },
  gulp.parallel(
    ['zip-plugins', 'zip-theme']
  )
));
