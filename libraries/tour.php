<?php
class Vr360Tour extends Vr360Object
{

	public function render()
	{
		Vr360Layout::load('body.user.tours.tour', array('tour' => $this));
	}
	public function save()
	{
		return Vr360Database::getInstance()->updateTour($this->getProperties());
	}
}