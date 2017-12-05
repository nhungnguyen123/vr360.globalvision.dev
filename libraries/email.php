<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360Email
 *
 * @since  2.0.0
 */
class Vr360Email extends PHPMailer\PHPMailer\PHPMailer
{
	/**
	 * Vr360Email constructor.
	 *
	 * @param null $exceptions
	 *
	 * @throws \PHPMailer\PHPMailer\Exception
	 */
	public function __construct($exceptions = null)
	{
		parent::__construct($exceptions);

		$configuration    = Vr360Configuration::getInstance();
		$this->SMTPDebug  = 2;
		$this->Mailer     = $configuration->mailer;
		$this->Host       = $configuration->mailHost;
		$this->SMTPAuth   = true;
		$this->Username   = $configuration->mailUsername;
		$this->Password   = $configuration->mailPassword;
		$this->SMTPSecure = $configuration->mailSecure;
		$this->Port       = $configuration->mailPort;

		// Send mail from
		$this->setFrom('no-reply@globalvision.ch', $configuration->siteName);
	}
}
