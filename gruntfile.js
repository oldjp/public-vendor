'use strict';

var fs = require('fs');

module.exports = function (grunt) {
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
    grunt.loadTasks('tasks/');

    grunt.initConfig({
        clean: ['test/grunt/fixtures/dest'],
        publicVendor: {
            options:{
                aliases: {
                    'bootstrap' : 'twitter/bootstrap',
                    'jquery' : 'components/jquery'
                },
                subPaths: {
                    'twitter/bootstrap' : 'dist'
                },
                baseDir: 'vendor'
            }
        },
        useminPrepare: {
            html: [
                'tests/grunt/fixtures/index.html'
            ],
            options: {
                dest: 'tests/grunt/fixtures/dest'
            }
        },

    });

    grunt.registerTask('resumeTestBuild', 'Checks if specified in test html file assets were created', function(){
        grunt.file.expand({filter:'isFile'}, [
            'tests/grunt/fixtures/dest/script/script.js', 
            'tests/grunt/fixtures/dest/styles/styles.css']
        ).map(function(file){
            var stat = fs.lstatSync(file);
            if(stat.size){
                grunt.log.ok('File: '+file+' exists');
            }   

        });
    });

    grunt.registerTask('test-build', [
        'clean',
        'publicVendor',
        'useminPrepare',
        'concat',
        'uglify',
        'cssmin',
        'resumeTestBuild'
    ]);
}
