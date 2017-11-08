<?php

use Step\LoginSteps as LoginSteps;
use Step\NewTourSteps as NewTourSteps;
class NewTourCest
{

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
        $this->userName = 'bory';
        $this->pass = '-vietvu-';

        $this->nameTour = $this->faker->bothify('nametour?##?');
        $this->url = $this->faker->bothify('url?##?');
        $this->title = $this->faker->bothify('title?##?');
        $this->description = $this->faker->bothify('description?##?');
        //edit name tour
        $this->nameTourEdit = $this->nameTour.'edit';
        $this->urlEdit = $this->url.'edit';

    }

    public function checkMissing(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
//        $I->checkMissing($this->nameTour,$this->url,$this->title,$this->description);
    }
    public function createNew(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
        $I->create($this->nameTour, $this->url, $this->title, $this->description);
    }

    public function editTour(NewTourSteps $I)
    {
        $I->login($this->userName, $this->pass);
        $I->editTour($this->nameTour,$this->nameTourEdit,$this->url);
        $I->delete($this->nameTourEdit);
    }

}