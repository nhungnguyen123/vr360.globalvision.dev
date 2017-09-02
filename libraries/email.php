<?php


class Vr360Email extends PHPMailer\PHPMailer\PHPMailer
{
	protected $mail;

	public function __construct($exceptions = null)
	{
		parent::__construct($exceptions);

		$configuration   = Vr360Configuration::getInstance();
		$this->SMTPDebug = 2;
		$this->isSMTP();                                      // Set mailer to use SMTP
		$this->Host       = $configuration->get('mailHost');  // Specify main and backup SMTP servers
		$this->SMTPAuth   = true;                               // Enable SMTP authentication
		$this->Username   = $configuration->get('mailUsername');                 // SMTP username
		$this->Password   = $configuration->get('mailPassword');                           // SMTP password
		$this->SMTPSecure = $configuration->get('mailSecure');                            // Enable TLS encryption, `ssl` also accepted
		$this->Port       = $configuration->get('mailPost');

		$this->setFrom('no-reply@globalvision.ch', $configuration->siteName);
	}
}