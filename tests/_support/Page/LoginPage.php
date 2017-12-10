<?php

namespace Page;

/**
 * Class LoginPage
 *
 * @package Page
 */
class LoginPage
{
	public static $usernameField = ['xpath' => "//input[@name='username']"];

	public static $passfield = ['xpath' => "//input[@name='password']"];

	public static $btnLogin = ['xpath' => ' //input[@name=\'submit\']'];

	public static $logoXpath = ['xpath' => ' //img[@id=\'logo\']'];

	public static $warningUserNameOrPassXpath = ['xpath' => '//div[@class=\'label label-warning\']'];

	public static $warningUsername = ['xpath' => '//div[@class=\'label label-default\']'];

	public static $warningUserNameOrPassXpathMessage = 'Invalid Username or password';

	public static $warningUsernameMessage = 'Invalid username';

	public static $warningInvalidPassMessage = 'Invalid password';

	public static $logoId = ['id' => 'logo'];

	/**
	 * @var array
	 */
	public static $faceIcon = ['xpath' => '//a[@id=\'social-facebook\']'];

	/**
	 * @var string
	 */
	public static $faceURL = 'https://www.facebook.com/globalvision360/';

	/**
	 * @var array
	 */
	public static $tweeticon = ['xpath' => '//a[@id=\'social-twitter\']'];

	/**
	 * @var string
	 */
	public static $twitterURL = 'https://twitter.com/GlobalVision360';

	/**
	 * @var array
	 */
	public static $googleIcon = ['xpath' => '//a[@id=\'social-googleplus\']'];

	/**
	 * @var string
	 */
	public static $googleURL = 'https://plus.google.com/+GlobalVisionSwitzerland';

	/**
	 * @var string
	 */
	public static $pageMain = 'http://globalvision.ch/en/home-page/';
}
