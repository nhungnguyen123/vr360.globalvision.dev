<?php

use Step\LoginSteps as LoginSteps;
use Step\NewTourSteps as NewTourSteps;
class NewTourCest
{

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->userName = 'qa';
        $this->pass = 'qa123';


        $this->nameTour = $this->faker->bothify('nametour?##?');
        $this->nameTourAlreday = $this->faker->bothify('nametour?##?');
        $this->url = $this->faker->bothify('URLFace?####??');
        $this->title = $this->faker->bothify('Title?##?');
        $this->description = $this->faker->bothify('Description?##?');
        //edit name tour
        $this->nameTourEdit = $this->nameTour.'edit';
        $this->urlEdit = $this->url.'edit';


    }

    public function createNew(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
        $I->create($this->nameTour, $this->url, $this->title, $this->description);
    }
    public function preview(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
        $I->preview($this->nameTour, $this->url, $this->title);
    }

    public function createReady(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
        $I->createWithURLReady($this->nameTourAlreday, $this->url,$this->title, $this->description);
    }
   
    public function editTour(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
        $I->editTour($this->nameTour,$this->nameTourEdit,$this->url);
        $I->delete($this->nameTourEdit);
        $I->wantTo('Check Close button ');
        $I->createWithClose($this->nameTour, $this->url, $this->title, $this->description);
    }

}