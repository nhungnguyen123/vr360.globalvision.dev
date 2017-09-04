<?php

defined('_VR360') or die;

/**
 * Class Vr360Email
 */
class Vr360Email extends PHPMailer\PHPMailer\PHPMailer
{
	protected $mail;

	public function __construct($exceptions = null)
	{
		parent::__construct($exceptions);

		$configuration   = Vr360Configuration::getInstance();
		$this->SMTPDebug = 2;
		$this->isSMTP();                                      // Set mailer to use SMTP
		$this->Host       = $configuration->mailHost;  // Specify main and backup SMTP servers
		$this->SMTPAuth   = true;                               // Enable SMTP authentication
		$this->Username   = $configuration->mailUsername;                 // SMTP username
		$this->Password   = $configuration->mailPassword;                           // SMTP password
		$this->SMTPSecure = $configuration->mailSecure;                            // Enable TLS encryption, `ssl` also accepted
		$this->Port       = $configuration->mailPost;

		$this->setFrom('no-reply@globalvision.ch', $configuration->siteName);
	}
}