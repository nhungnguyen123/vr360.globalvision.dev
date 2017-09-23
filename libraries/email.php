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
	 * @param   null $exceptions
	 */
	public function __construct($exceptions = null)
	{
		parent::__construct($exceptions);

		$configuration    = Vr360Configuration::getInstance();
		$this->SMTPDebug  = 2;
		$this->Mailer     = $configuration->mailer;                                  // Set mailer to use SMTP
		$this->Host       = $configuration->mailHost;  // Specify main and backup SMTP servers
		$this->SMTPAuth   = true;                               // Enable SMTP authentication
		$this->Username   = $configuration->mailUsername;                 // SMTP username
		$this->Password   = $configuration->mailPassword;                           // SMTP password
		$this->SMTPSecure = $configuration->mailSecure;                            // Enable TLS encryption, `ssl` also accepted
		$this->Port       = $configuration->mailPort;

		// Send mail from
		$this->setFrom('no-reply@globalvision.ch', $configuration->siteName);
	}
}
