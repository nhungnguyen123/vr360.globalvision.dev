<?php

class Vr360Tour extends Vr360DatabaseTable
{

	protected $_table = 'tbl_vtour';

	public function render()
	{
		Vr360Layout::load('body.user.tours.tour', array('tour' => $this));
	}
}