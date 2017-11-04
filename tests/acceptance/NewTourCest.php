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
        $this->url = 'TestTour';
        $this->title = 'This is title';
        $this->description = 'This is description';
    }

    public function createNew(NewTourSteps $I)
    {
        $I->comment('Do login with Bory');
        $I->login($this->userName, $this->pass);
        $I->create($this->nameTour, $this->url, $this->title, $this->description);
    }

}