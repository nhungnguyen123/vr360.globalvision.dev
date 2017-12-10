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
	public static $fieldName = ['xpath' => './/input[@name=\'name\']'];

	/**
	 * @var array
	 */
	public static $fieldFriendlyURL = ['xpath' => '//input[@name=\'alias\']'];

	/**
	 * @var array
	 */
	public static $inputTourDescription = ['xpath' => '//input[@name=\'description\']'];

	/**
	 * @var array
	 */
	public static $inputTourKeyword = ['xpath' => '//input[@name=\'keyword\']'];

	/**
	 * @var array
	 */
	public static $checkAutoRation = ['xpath' => '//input[@id=\'tour_rotation\']'];

	/**
	 * @var array
	 */
	public static $checkShowMedia = ['xpath' => '//input[@id=\'tour_social\']'];

	public static $fieldTitleFirst = ['xpath' => '(//div[@id=\'panoWrap\']/div[2]/input)[2]'];

	public static $fieldDescriptionFirst = ['xpath' => '(//div[@id=\'panoWrap\']/div[3]/input)[2]'];

	public static $btnAddImageFirst = ['xpath' => '(//div[@id=\'panoWrap\']/div[1]/input)[2]'];

	public static $btnClose = ['id' => 'modal-close'];

	/**
	 * @uses    Only get xpath for required class NOT whole class
	 * @uses    And this button must be inside parent "scenes"
	 *
	 * @var array
	 */
	public static $buttonRemove = ['xpath' => '//*[@id="scenes"]//*/button[contains(concat(\' \', @class, \' \'), \' removeScene \')]'];

	public static $btnRemovePano = ['xpath' => '//div[@id=\'tour-panos\']/div/div[1]/button'];

	/**
	 * @var array
	 */
	public static $btnCreate = ['xpath' => '//button[@id=\'saveTour\']'];

	/**
	 * @var array
	 */
	public static $btnAddPano = ['xpath' => '//button[@id=\'addScene\']'];

	public static $missingField = ['xpath' => '//span[@class=\'help-block form-error\']'];

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