'use strict';

var path = require('path');

module.exports = function(grunt) {
	grunt.registerTask('publicVendor', 'Add alternative assets path resolver for composer vendor packages', function(){
		var options = this.options(),
			processor = function(context, block){
				var inFiles = [], 
					returns = {
                        dest: path.join(context.outDir, block.dest),
                        src: []
                    };

                context.inFiles.forEach(function(file) {
                    var source = path.join(context.inDir, file);
                    
                    if(/^\/vendor/.test(file) && !grunt.file.exists(source)){
                                        
                        var elements = file.split(path.sep),
                        	vendor = '',
                        	pathfile = '';

                        while('vendor' != elements.shift());
                                        
                        vendor = elements.shift(), pathfile;
                        
                        if(typeof options.aliases[vendor] != 'undefined'){
                            vendor = options.aliases[vendor];
                        }else{
                            vendor = path.join(vendor, elements.shift());
                        }
                                        
                        pathfile = elements.join(path.sep);
                        
                        if(typeof options.subPaths[vendor] != 'undefined'){
                            pathfile = path.join(options.subPaths[vendor], pathfile)
                        }

                        source = path.join(options.baseDir, vendor, pathfile);
                    }
                    
                    inFiles.push(source);
                });

                grunt.file.expand({filter:'isFile'}, inFiles).map(function(file){
                    returns.src.push(file);                                    
                });

                context.outFiles = [block.dest];
                return {files:[returns]};
			},
			config = grunt.config('useminPrepare');

		if(typeof config.options['flow'] == 'undefined'){
			config.options.flow = {
				steps: {
					js: [{name: 'concat', createConfig: processor}, 'uglifyjs'],
					css: [{name: 'concat', createConfig: processor}, 'cssmin']
				},
				post:{}
			}
			grunt.config('useminPrepare', config);
		}
	});
}