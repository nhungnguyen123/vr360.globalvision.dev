<?php

class Vr360User extends Vr360DatabaseTable
{

	public $id = null;
	public $username = null;
	public $name = null;
	public $password = null;
	public $email = null;
	public $last_login = null;
	public $last_visit = null;
	public $params = null;

	protected $_table = 'users';
}