<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360HelperDatetime
 *
 * @since  2.0.0
 */
class Vr360HelperDatetime
{
	/**
	 * @return false|string
	 */
	public static function getMySqlFormat()
	{
		return date('Y-m-d H:i:s');
	}
}
