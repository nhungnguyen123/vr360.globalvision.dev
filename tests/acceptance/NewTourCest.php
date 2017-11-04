<?php

use Step\LoginSteps as LoginSteps;
use Step\NewTourSteps as NewTourSteps;
class NewTourCest
{

    public function __construct()
    {
        $this->userName = 'bory';
        $this->pass = '-vietvu-';
        $this->nameTour = 'NewTour';
        $this->url = 'testtour';
        $this->title = 'This is title';
        $this->description = 'This is description';

        //edit name tour
        $this->nameTourEdit = 'edit';

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