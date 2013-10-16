'use strict';

var assert = require('assert');
var concat = require('../../src/grunt/concat.js');
var grunt = require('grunt');

var context = { 
	inDir: 'test/grunt/fixtures',
  	inFiles:[ 
  		'/vendor/bootstrap/css/bootstrap.css',
     	'/vendor/bootstrap/css/bootstrap-theme.css' 
 	],
  	outFiles: [],
  	outDir: '.tmp/concat',
  	last: false,
  	options: { 
  		generated: {} 
  	} 
};

var block = {
	type: 'css',
	dest: '/styles/styles.css',
	startFromRoot: false,
	indent: '\t',
	searchPath: [ 'test/grunt/fixtures' ],
	src:[ 
		'/vendor/bootstrap/css/bootstrap.css',
    	'/vendor/bootstrap/css/bootstrap-theme.css' 
	],
  	raw:[ 
		'\t<!-- build:css(test/grunt/fixtures) /styles/styles.css -->',
     	'\t<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.css">',
     	'\t<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap-theme.css">',
     	'\t<!-- endbuild -->' 
 	],
  	defer: false
};

var config = {
    aliases: {
        'bootstrap' : 'twitter/bootstrap',
        'jquery' : 'components/jquery'
    },
    subPaths: {
        'twitter/bootstrap' : 'dist'
    },
    baseDir: 'vendor'
}


describe('Concat processor', function(){

	concat.init(grunt, config);

	it('Should have proper CSS paths', function(){
		var resp = concat.createConfig(context, block);

		assert.equal('.tmp/concat/styles/styles.css', resp.files[0].dest);
		assert.equal('vendor/twitter/bootstrap/dist/css/bootstrap.css', resp.files[0].src[0]);
	});
});