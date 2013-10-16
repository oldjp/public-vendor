<?php

namespace PublicVendor;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @covers PublicVendor\Container::setPath
	 */
	public function testPath()
	{
		$path = dirname(dirname(PROJECT_PATH));

		$container = new Container();
		$this->assertEquals($path, $container->getPath());
	}

	/**
	 * @expectedException PublicVendor\Exception
	 * @covers PublicVendor\Container::setPath
	 */
	public function testFakePath()
	{
		do{
			$fakePath = '/'.md5(microtime());
		} while(file_exists($fakePath));

		$container = new Container($fakePath);
	}

	/**
	 * @covers PublicVendor\Container::resolve
	 * @dataProvider resoveProvider
	 */
	public function testResolve($vendor, $file)
	{
		$container = new Container(VENDOR_PATH);
		$container->addSubPath('twitter/bootstrap', 'dist');

		$this->assertTrue(file_exists($container->resolve($vendor, $file)));
	}

	public function resoveProvider()
	{
		return array(
			array('pimple/pimple', 'composer.json'),
			array('twitter/bootstrap', 'js/bootstrap.js')
		);
	}

	/**
	 * @covers PublicVendor\Container::resolve
	 */
	public function testFakeFile()
	{
		$container = new Container(VENDOR_PATH);
		$result = $container->resolve('fake/vendor', 'fake/file.html');

		$this->assertFalse(file_exists($result));
	}
}