'use strict';

var path = require('path');

module.exports = function (grunt) {
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
    grunt.loadTasks('tasks/');

    grunt.initConfig({
        publicVendor: {
            options:{
                aliases: {
                    'bootstrap' : 'twitter/bootstrap'
                },
                subPaths: {
                    'twitter/bootstrap' : 'dist'
                },
                baseDir: 'vendor'
            }
        },
        useminPrepare: {
            html: [
                'tests/grunt/fixtures/*.html'
            ],
            options: {
                dest: 'tmp/'
            }
        }
    });

    grunt.registerTask('build', [
        'publicVendor',
        'useminPrepare',
        'concat',
        'uglify',
        'cssmin'
    ]);
}
