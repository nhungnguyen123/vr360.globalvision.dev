<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Layout
 *
 * @since  2.0.0
 */
class Vr360Layout
{
	/**
	 * @var null|string
	 */
	protected $layoutBase;

	/**
	 * Vr360Layout constructor.
	 *
	 * @param   string $baseDir Base directory path
	 */
	public function __construct($baseDir = null)
	{
		if ($baseDir === null)
		{
			$baseDir = VR360_PATH_LAYOUTS;
		}

		$this->layoutBase = $baseDir;
	}

	/**
	 * @return static
	 */
	public static function getInstance()
	{
		static $instance;

		if (isset($instance))
		{
			return $instance;
		}

		$instance = new static;

		return $instance;
	}

	public function load($layout, $data = array())
	{
		extract($data);
		require $this->layoutBase . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $layout) . '.php';
	}

	public function fetch($layout, $data = array())
	{
		ob_start();
		extract($data);
		require $this->layoutBase . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $layout) . '.php';

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}
