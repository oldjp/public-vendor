<?php

namespace PublicVendor;


class Container
{

	/**
	 * List of vendor specific assets subpaths
	 *
	 * @var array
	 */
	protected $subpaths = array();

	/**
	 * List of alias names of vendors
	 * 
	 * @var array
	 */
	protected $aliases = array();

	/**
	 * Vendor folter path
	 *
	 * @var String
	 */
	protected $path;

	/**
	 * Constructor - setup vendor path
	 * 
	 * @param string $path
	 */
	public function __construct($path = null)
	{
		$this->setPath($path);	
	}

	/**
	 * Add vendor specific subpath
	 * 
	 * @param String $vendor
	 * @param String $path
	 */
	public function addSubPath($vendor, $path)
	{
		$this->subpaths[$vendor] = $path;
	}

	/**
	 * Add alias name of vendor
	 *
	 * 'twitter/bootstrap' => 'bootstrap'
	 * 
	 * @param string $vendor 
	 * @param alias $alias 
	 */
	public function addAlias($vendor, $alias)
	{
		$this->aliases[$alias] = $vendor;
	}

	/**
	 * Return vendor path
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Set vendor path
	 * 
	 * @param string $path
	 * @throws PublicVendor\\Exception If path isn't exists
	 */
	public function setPath($path = null)
	{
		if(null === $path) {
			$path = realpath(__DIR__.'/../../../..');
		}

		if(!file_exists($path)) {
			throw new Exception('Invalid vendor folder path');
		}

		$this->path = $path;
	}

	/**
	 * Resolve vendor name and path to file in vendor directory
	 * 
	 * @param  string $vendor
	 * @param  string $path
	 * @return string
	 */
	public function resolve($vendor, $path, $project = null){
		if(isset($this->aliases[$vendor])){
			$path = null === $project ? $path : $project . DIRECTORY_SEPARATOR . $path;
			$vendor = $this->aliases[$vendor];
		}else{
			$vendor .= null === $project ? '' : DIRECTORY_SEPARATOR . $project;
		}

		return $this->path 
		. DIRECTORY_SEPARATOR . $vendor
		. DIRECTORY_SEPARATOR . ( 
			isset($this->subpaths[$vendor]) ? 
				$this->subpaths[$vendor].DIRECTORY_SEPARATOR : 
				'' 
		).$path;
	}
}

