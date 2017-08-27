<?php
class Vr360User extends Vr360Object
{

	public function save()
	{
		return Vr360Database::getInstance()->updateUser($this->getProperties());
	}
}