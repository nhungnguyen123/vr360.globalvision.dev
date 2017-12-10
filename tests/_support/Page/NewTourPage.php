<?php
/**
 * Created by PhpStorm.
 * User: nhung
 * Date: 04/11/2017
 * Time: 07:24
 */

namespace Page;

/**
 * Class NewTourPage
 *
 * @uses    For most of input please use @name instead ID because it's constant
 * @uses    To use class, make sure use unique class only not all class
 *
 * @package Page
 */
class NewTourPage
{
	/**
	 * @var array
	 */
	public static $fieldName = ['xpath' => '//input[@name=\'name\']'];

	/**
	 * @var array
	 */
	public static $fieldFriendlyURL = ['xpath' => '//input[@name=\'alias\']'];


	/**
	 * @var array
	 */
	public static $checkAutoRation = ['xpath' => '//input[@id=\'tour_rotation\']'];

	/**
	 * @var array
	 */
	public static $checkShowMedia = ['xpath' => '//input[@id=\'tour_social\']'];

    
	/**
	 * @uses    Only get xpath for required class NOT whole class
	 * @uses    And this button must be inside parent "scenes"
	 *
	 * @var array
	 */
	public static $buttonRemoveScene = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \' removeScene \')]'];


	/**
	 * @var array
	 */
	public static $btnCreate = ['xpath' => '//button[@id=\'saveTour\']'];

	/**
	 * @var array
	 */
	public static $btnAddPano = ['xpath' => '//button[@id=\'addScene\']'];

    /**
     * @var array
     */
	public static $missingField = ['xpath' => '//span[contains(concat(\' \', @class, \' \'), \' form-error \')]'];

	/**
	 * @var string
	 */
	public static $messageMissingField = 'This is a required field';

	//image
	public static $imageFirst = '1.jpg';

	public function nameField($value)
	{
		$xpath = '(//input[@name=\'newSceneName[]\'])[' . $value . ']';

		return $xpath;
	}

	public function descriptionField($value)
	{
		$xpath = '(//input[@name=\'newSceneDescription[]\'])[' . $value . ']';

		return $xpath;
	}

	public function imageInput($value)
	{
		$xpath = '(//input[@name=\'newSceneFile[]\'])[' . $value . ']';

		return $xpath;
	}
}