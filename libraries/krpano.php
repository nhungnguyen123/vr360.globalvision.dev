<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Krpano
 */
class Vr360Krpano
{
	protected $binPath;
	protected $license;
	protected $parameters = array();
	protected $files = array();

	/**
	 * Vr360Krpano constructor.
	 *
	 * @param $binPath
	 * @param $license
	 */
	public function __construct($binPath, $license)
	{
		$this->binPath = Vr360Configuration::getConfig('krPanoPath');
		$this->license = Vr360Configuration::getConfig('krPanoLicense');
	}

	public function addFiles($files)
	{
		$this->files = $files;
	}

	public function useConfigFile($file)
	{
		$this->addParameter('-config=' . Vr360HelperFile::clean($file));
	}

	public function addParameter($parameter)
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * @param  $command
	 *
	 * @return bool|string
	 */
	public function makePano(&$command)
	{
		if ($this->validate() === false)
		{
			return false;
		}

		$execute[] = $this->binPath . ' register ' . $this->license;

		// Make pano
		array_unshift($this->parameters, 'makepano');
		$execute[] = $this->binPath . ' ' . implode(' ', $this->parameters) . ' ' . implode(' ', $this->files);

		$command = implode(' && ', $execute);

		return exec($command);
	}

	protected function validate()
	{
		if (empty($this->files))
		{
			return false;
		}

		foreach ($this->files as $file)
		{
			if (!Vr360HelperFile::exists($file))
			{
				return false;
			}
		}

		return true;
	}
}