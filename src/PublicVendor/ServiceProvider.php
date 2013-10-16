<?php

namespace PublicVendor;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		$app['public-vendor._default_'] = 'application/octet-stream';
		$app['public-vendor.css'] 		= 'text/css';
		$app['public-vendor.js'] 		= 'application/javascript';
		$app['public-vendor.html']		= 'text/html';
		$app['public-vendor.eot']		= 'application/vnd.ms-fontobject';
		$app['public-vendor.svg']		= 'image/svg+xml';
		$app['public-vendor.ttf']		= 'application/x-font-ttf';
		$app['public-vendor.woff']		= 'application/font-woff';
		$app['public-vendor.jpg']		= 'image/jpeg';
		$app['public-vendor.png']		= 'image/png';
		$app['public-vendor.jpeg']		= 'image/jpeg';
		$app['public-vendor.gif']		= 'image/gif';
		$app['public-vendor.ico']		= 'image/x-icon';

		$app['public-vendor'] = $app->share(function() use ($app){
			return new Container();
		});

		$app['public-vendor.response'] 	= $app->protect(function($file) use ($app){
			if(!file_exists($file)){
				$app->abort(404, "Not found");
			}
			$name = 'public-vendor.'.strtolower(pathinfo($file, PATHINFO_EXTENSION));
			return $app->sendFile($file, 200, array('Content-type' => isset($app[$name]) ? $app[$name] : $app['public-vendor._default_']));
		});
	}

	public function boot(Application $app)
	{
		$app->get('/vendor/{vendor}/{project}/{file}', function($vendor, $project, $file) use ($app){
			return $app['public-vendor.response']($app['public-vendor']->resolve($vendor, $file, $project));
		})->assert('file', '.*');

		$app->get('/vendor/{alias}/{file}', function($alias, $file) use ($app){
			return $app['public-vendor.response']($app['public-vendor']->resolve($alias, $file));
		});
	}
}




