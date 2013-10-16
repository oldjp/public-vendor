'use strict';

var path = require('path'),
	grunt,
	options;

exports.name = 'concat';

exports.init = function(){
	grunt = arguments[0];
	options = arguments[1];
}

exports.createConfig = function(context, block){
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

    console.log(returns);
    context.outFiles = [block.dest];
    return {files:[returns]};
}