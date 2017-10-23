<?php


class AdminLoginCest
{
    public function login(AcceptanceTester $I)
    {
        $I->amOnPage('');
        $I->comment('Fill Username Text Field');
        // $I->wait(30);
        $I->fillField(['xpath'=>"//input[@name='username']"], 'designteam');
        $I->comment('Fill Password Text Field');
        $I->fillField("//input[@name='password']", 'nQ-yQ?3ba');
        $I->comment('I click Login button');
        $I->click("//input[@name='']");
        $I->waitForElement("//img[@id='logo']",30);
        $I->wait(5);

        $I->comment('I see Administrator Control Panel');
        // $I->see('Control Panel', '.page-title');
    }
}
