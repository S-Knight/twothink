'use strict';

// sass compile
var gulp = require('gulp');
var minifyCss = require("gulp-minify-css");
var rename = require("gulp-rename");
var uglify = require("gulp-uglify");

//*** CSS & JS minify task
gulp.task('default', function () {
    // css minify 
    gulp.src(['./apps/css/*.css', '!./apps/css/*.min.css']).pipe(minifyCss()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./apps/css/'));

    gulp.src(['./global/css/*.css','!./global/css/*.min.css']).pipe(minifyCss()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./global/css/'));
    gulp.src(['./pages/css/*.css','!./pages/css/*.min.css']).pipe(minifyCss()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./pages/css/'));    
    
    gulp.src(['./layouts/**/css/*.css','!./layouts/**/css/*.min.css']).pipe(rename({suffix: '.min'})).pipe(minifyCss()).pipe(gulp.dest('./layouts/'));
    gulp.src(['./layouts/**/css/**/*.css','!./layouts/**/css/**/*.min.css']).pipe(rename({suffix: '.min'})).pipe(minifyCss()).pipe(gulp.dest('./layouts/'));

    gulp.src(['./global/plugins/bootstrap/css/*.css','!./global/plugins/bootstrap/css/*.min.css']).pipe(minifyCss()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./global/plugins/bootstrap/css/'));

    //js minify
    gulp.src(['./apps/scripts/*.js','!./apps/scripts/*.min.js']).pipe(uglify()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./apps/scripts/'));
    gulp.src(['./global/scripts/*.js','!./global/scripts/*.min.js']).pipe(uglify()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./global/scripts'));
    gulp.src(['./pages/scripts/*.js','!./pages/scripts/*.min.js']).pipe(uglify()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./pages/scripts'));
    gulp.src(['./layouts/**/scripts/*.js','!./layouts/**/scripts/*.min.js']).pipe(uglify()).pipe(rename({suffix: '.min'})).pipe(gulp.dest('./layouts/'));
});