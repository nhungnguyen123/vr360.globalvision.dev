<?php
/**
 * Created by PhpStorm.
 * User: nhung
 * Date: 04/11/2017
 * Time: 07:24
 */

namespace Step;

use Page\NewTourPage as NewTourPage;
use Page\ManagePage as ManagePage;

class NewTourSteps extends ManageSteps
{

    public function create($name, $url, $title, $description)
    {
        $I = $this;
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->switchToIFrame();
        $I->comment('Fill Name Text Field');
        $I->wait(3);
        $I->fillField(NewTourPage::$fieldName, $name);
        $I->comment('Fill URL Text Field');
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);

        $I->waitForElement(NewTourPage::$fieldTitleFirst,30);
        $I->wait(3);

        $I->fillField(NewTourPage::$fieldTitleFirst,$title);
        $I->fillField(NewTourPage::$fieldDescriptionFirst,$description);

        $I->attachFile(NewTourPage::$btnAddImageFirst,NewTourPage::$imageFirst );

        $I->comment('I click Login button');
        $I->click(NewTourPage::$btnCreate);
        $I->wait(500);
        $I->waitForElement(ManagePage::$btnLogout,60);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->see($url,ManagePage::$urlValue);
        $I->comment('I see Administrator Control Panel');
    }

    public function checkMissing($name, $url, $title, $description)
    {

    }
}