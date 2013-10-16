<?php

namespace PublicVendor;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{

	protected function getApplication()
	{
		$app = new Application();
	}

	protected function getApplicationWithServiceProvider()
	{
		$app = new Application();
		$app->register(new ServiceProvider());
		$app['public-vendor']->setPath(VENDOR_PATH);
		return $app;
	}

	public function testContainer()
	{
		$app = $this->getApplicationWithServiceProvider();
		$this->assertTrue(isset($app['public-vendor']));
		$this->assertInstanceOf('PublicVendor\\Container', $app['public-vendor']);
	}

	public function testNotFound()
	{
		$app = $this->getApplicationWithServiceProvider();
		$response = $app->handle(Request::create('/vendor/fake/vendor/fake/file.html'));
		
		$this->assertEquals(404, $response->getStatusCode());
	}


	/**
	 * @dataProvider resoveProvider
	 */
	public function testFileWithContentType($path, $contentType)
	{
		$app = $this->getApplicationWithServiceProvider();
		$response = $app->handle(Request::create($path));

		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals($contentType, $response->headers->get('content-type'));
	}

	public function resoveProvider()
	{
		return array(
			array('/vendor/twitter/bootstrap/about.html'											, 'text/html'),
			array('/vendor/twitter/bootstrap/dist/js/bootstrap.js'									, 'application/javascript'),
			array('/vendor/twitter/bootstrap/dist/css/bootstrap.css'								, 'text/css'),
			array('/vendor/twitter/bootstrap/dist/fonts/glyphicons-halflings-regular.eot'			, 'application/vnd.ms-fontobject'),
			array('/vendor/twitter/bootstrap/dist/fonts/glyphicons-halflings-regular.svg'			, 'image/svg+xml'),
			array('/vendor/twitter/bootstrap/dist/fonts/glyphicons-halflings-regular.ttf'			, 'application/x-font-ttf'),
			array('/vendor/twitter/bootstrap/dist/fonts/glyphicons-halflings-regular.woff'			, 'application/font-woff'),
			array('/vendor/twitter/bootstrap/examples/screenshots/grid.jpg'							, 'image/jpeg'),
			array('/vendor/twitter/bootstrap/docs-assets/ico/favicon.png'							, 'image/png')
		);
	}

	public function testFileWithSubPath()
	{
		$app = $this->getApplicationWithServiceProvider();
		$app['public-vendor']->addSubPath('twitter/bootstrap', 'dist');

		$response = $app->handle(Request::create('/vendor/twitter/bootstrap/js/bootstrap.js'));
		$this->assertEquals(200, $response->getStatusCode());
	}

	public function testFileWithAlias()
	{
		$app = $this->getApplicationWithServiceProvider();
		$app['public-vendor']->addAlias('twitter/bootstrap', 'bootstrap');

		$response = $app->handle(Request::create('/vendor/bootstrap/dist/js/bootstrap.js'));
		$this->assertEquals(200, $response->getStatusCode());	
	}

	public function testFileWithAliasAndSubPath()
	{
		$app = $this->getApplicationWithServiceProvider();
		$app['public-vendor']->addAlias('twitter/bootstrap', 'bootstrap');
		$app['public-vendor']->addSubPath('twitter/bootstrap', 'dist');

		$response = $app->handle(Request::create('/vendor/bootstrap/js/bootstrap.js'));
		$this->assertEquals(200, $response->getStatusCode());
	}
}