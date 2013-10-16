'use strict';

var path = require('path');
var concat = require('../src/grunt/concat.js');

module.exports = function(grunt) {
	grunt.registerTask('publicVendor', 'Add alternative assets path resolver for composer vendor packages', function(){
		
		var options = this.options(), 
			config = grunt.config('useminPrepare');

		concat.init(grunt, options);
		if(typeof config.options['flow'] == 'undefined'){
			config.options.flow = {
				steps: {
					js: [concat, 'uglifyjs'],
					css: [concat, 'cssmin']
				},
				post:{}
			}
			grunt.config('useminPrepare', config);
		}
	});
}