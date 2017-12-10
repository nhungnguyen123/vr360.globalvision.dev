<?php

use Step\LoginSteps as LoginSteps;
use Step\NewTourSteps as NewTourSteps;

/**
 * Class NewTourCest
 */
class NewTourCest
{
	/**
	 * NewTourCest constructor.
	 */
	public function __construct()
	{
		$this->faker    = Faker\Factory::create();

		/**
		 * @TODO Move these value to defines
		 */
		$this->userName = 'designteam';
		$this->pass     = '123';

		/**
		 * @TODO    Create name over than 255 character
		 * @TODO    Create name with UTF-8 characters
		 */
		$this->nameTour        = $this->faker->bothify('nametour?##???');
		$this->nameTourAlreday = $this->faker->bothify('nametour?##???');
		$this->url             = $this->faker->bothify('URLFace?#######??');
		$this->title           = $this->faker->bothify('Title?##?');
		$this->description     = $this->faker->bothify('Description?##?');

		//edit name tour
		$this->nameTourEdit = $this->nameTour . 'edit';
		$this->urlEdit      = $this->url . 'edit';
	}

	/**
	 * @param NewTourSteps $I
	 * create new tour without any screen
	 */
    public function createWithoutAnyScreen(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
        $I->createWithoutAnyScreen($this->nameTour);
    }

	/**
	 * @param NewTourSteps $I
	 * Check missing for all cases
	 */
	public function checkMissing(NewTourSteps $I)
	{
		$I->login($this->userName, $this->pass);
		$I->checkMissing($this->nameTour, $this->url, $this->title, $this->description);
	}

	/**
	 * @param NewTourSteps $I
	 * Create new tour
	 */
	public function createNew(NewTourSteps $I)
	{
		$I->login($this->userName, $this->pass);
		$I->create($this->nameTour, $this->url, $this->title, $this->description);
	}

	/**
	 * @param NewTourSteps $I
	 * Preview tour
	 */
	public function preview(NewTourSteps $I)
	{
		$I->login($this->userName, $this->pass);
		$I->preview($this->nameTour, $this->url, $this->title);
	}

	/**
	 * @param NewTourSteps $I
	 * Edit name for this tour
	 */
	public function editTour(NewTourSteps $I)
	{
		$I->login($this->userName, $this->pass);
		$I->editTour($this->nameTour, $this->nameTourEdit, $this->url);
	}

	/**
	 * @param NewTourSteps $I
	 * Create new tour with url is ready
	 */
	public function createReady(NewTourSteps $I)
	{
		$I->login($this->userName, $this->pass);
		$I->createWithURLReady($this->nameTourAlreday, $this->url);
	}

	/**
	 * @param NewTourSteps $I
	 * Delete tour
	 */
	public function delete(NewTourSteps $I)
	{
		$I->login($this->userName, $this->pass);
		$I->delete($this->nameTourEdit);
//        $I->wantTo('Check Close button ');
//        $I->createWithClose($this->nameTour, $this->url, $this->title, $this->description);
	}

}
