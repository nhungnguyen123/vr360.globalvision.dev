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

        $I->comment('I click Create button');
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

        $I = $this;
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->switchToIFrame();
        $I->wait(3);

        $I->comment('Missing field name');
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);

        $I->waitForElement(NewTourPage::$fieldTitleFirst,30);
        $I->wait(3);

        $I->fillField(NewTourPage::$fieldTitleFirst,$title);
        $I->fillField(NewTourPage::$fieldDescriptionFirst,$description);

        $I->attachFile(NewTourPage::$btnAddImageFirst,NewTourPage::$imageFirst );

        $I->comment('I click Create button');
        $I->click(NewTourPage::$btnCreate);
        $I->wait(100);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->dontSee($url,ManagePage::$urlValue);
        $I->comment('I see Administrator Control Panel');
    }
    
    public function editTour($name, $nameEdit, $url)
    {
        $I = $this;
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->see($url,ManagePage::$urlValue);
        $I->click(ManagePage::$btnEditFrirst);
        $I->wait(5);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->fillField(NewTourPage::$fieldName, $nameEdit);
        $I->click(NewTourPage::$btnCreate);
        $I->wait(30);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$nameEdit);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$nameTour,30);
        $I->see($nameEdit,ManagePage::$nameTour);
        $I->comment('I see Administrator Control Panel');
    }
    
    public function delete($name)
    {
        $I = $this;
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->click(ManagePage::$btnRemove);
        $I->acceptPopup();
        $I->wait(3);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($name,ManagePage::$nameTour);
        
        
    }
}