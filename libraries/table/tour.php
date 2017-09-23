<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TableTour
 *
 * @since  2.0.0
 */
class Vr360TableTour extends Vr360Table
{
	/**
	 * @var null
	 */
	public $id = null;
	/**
	 * @var null
	 */
	public $name = null;
	/**
	 * @var null
	 */
	public $description = null;
	/**
	 * @var null
	 */
	public $alias = null;
	/**
	 * @var null
	 */
	public $created = null;
	/**
	 * @var null
	 */
	public $created_by = null;
	/**
	 * @var null
	 */
	public $dir = null;
	/**
	 * @var null
	 */
	public $status = null;

	/**
	 * @var null
	 */
	public $params = null;

	/**
	 * @var string
	 */
	protected $_table = 'tours';

	/**
	 * @var  array
	 */
	protected $_errors;

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * @return bool
	 */
	protected function check()
	{
		$db    = Vr360Database::getInstance();
		$tours = $db->select(
			$this->_table,
			'*',
			array
			(
				'id[!]' => $this->id,
				'alias' => $this->alias
			)
		);

		if ($tours !== false && count($tours) > 0)
		{
			// Append ID
			$this->alias = $this->alias . '-' . $this->id;

			Vr360AjaxResponse::getInstance()->addWarning('Duplicated alias');
		}

		if (empty($this->name) || empty($this->alias) || empty($this->dir))
		{
			return false;
		}

		// Replace double byte whitespaces by single byte (East Asian languages)
		$str = preg_replace('/\xE3\x80\x80/', ' ', $this->alias);
		// Remove any '-' from the string as they will be used as concatenator.
		// Would be great to let the spaces in but only Firefox is friendly with this
		$str = str_replace('-', ' ', $str);
		// Replace forbidden characters by whitespaces
		$str = preg_replace('#[:\#\*"@+=;!><&\.%()\]\/\'\\\\|\[]#', "\x20", $str);
		// Delete all '?'
		$str = str_replace('?', '', $str);
		// Trim white spaces at beginning and end of alias and make lowercase
		$str = trim(strtolower($str));
		// Remove any duplicate whitespace and replace whitespaces by hyphens
		$str         = preg_replace('#\x20+#', '-', $str);
		$this->alias = $str;

		if ($this->created === null)
		{
			$this->created = Vr360HelperDatetime::getMySqlFormat();
		}

		if ($this->created_by === null)
		{
			$this->created_by = Vr360Factory::getUser()->id;
		}

		return parent::check();
	}
}
