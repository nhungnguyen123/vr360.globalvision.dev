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
        $I->comment('Fill Name Text Field');
        $I->wait(1);
        $I->fillField(NewTourPage::$fieldName, $name);
        $I->wait(1);
        $I->comment('Fill URL Text Field');
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);

        $I->click(NewTourPage::$btnAddPano);
        $usePage = new NewTourPage();

        $I->waitForElement($usePage->nameField(2), 30);
        $I->waitForElement($usePage->nameField(2),30);

        $I->fillField($usePage->nameField(2),$title);
        $I->fillField($usePage->descriptionField(2),$description);

        
        $I->attachFile($usePage->imageInput(2),NewTourPage::$imageFirst );

        $I->comment('I click Create button');
        $I->click(NewTourPage::$btnCreate);

        $I->wait(500);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->see($url,ManagePage::$urlValue);
        $I->waitForElement(ManagePage::$btnLogout,60);
        $I->click(ManagePage::$btnLogout);
        
    }

    public function createWithURLReady($name, $url, $title, $description)
    {
        $I = $this;
        $I->click(ManagePage::$btnAddNew);
        $I->comment('create Tour with url is already taken ');
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
        $I->dontSee($url,ManagePage::$urlValue);
    }
    public function checkMissing($name, $url, $title, $description)
    {

        $I = $this;
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->switchToIFrame();
        $I->wait(3);

        $I->comment('Missing attach image value');
        $I->fillField(NewTourPage::$fieldName, $name);
        $I->comment('Fill URL Text Field');
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);

        $I->waitForElement(NewTourPage::$fieldTitleFirst,30);
        $I->wait(3);

        $I->fillField(NewTourPage::$fieldTitleFirst,$title);
        $I->fillField(NewTourPage::$fieldDescriptionFirst,$description);
        $I->comment('I click Create button');
        $I->click(NewTourPage::$btnCreate);
        $I->wait(300);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($url,ManagePage::$urlValue);


        $I->comment('Missing field name');
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->switchToIFrame();
        $I->wait(3);
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);
        $I->waitForElement(NewTourPage::$fieldTitleFirst,30);
        $I->fillField(NewTourPage::$fieldTitleFirst,$title);
        $I->fillField(NewTourPage::$fieldDescriptionFirst,$description);
        $I->attachFile(NewTourPage::$btnAddImageFirst,NewTourPage::$imageFirst );
        $I->comment('I click Create button');
        $I->click(NewTourPage::$btnCreate);
        $I->wait(300);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->wait(10);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($url,ManagePage::$urlValue);

        $I->comment('Missing field URL ');
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->switchToIFrame();
        $I->wait(3);
        $I->fillField(NewTourPage::$fieldName, $name);
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
        $I->dontSee($url,ManagePage::$urlValue);


        $I->comment('Missing Title of first');
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->switchToIFrame();
        $I->wait(3);
        $I->fillField(NewTourPage::$fieldName, $name);
        $I->comment('Fill URL Text Field');
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);
        $I->waitForElement(NewTourPage::$fieldTitleFirst,30);
        $I->fillField(NewTourPage::$fieldDescriptionFirst,$description);
        $I->attachFile(NewTourPage::$btnAddImageFirst,NewTourPage::$imageFirst );
        $I->wait(100);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($url,ManagePage::$urlValue);


        $I->comment('Missing Description of first ');
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->switchToIFrame();
        $I->wait(3);
        $I->fillField(NewTourPage::$fieldName, $name);
        $I->comment('Fill URL Text Field');
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);

        $I->waitForElement(NewTourPage::$fieldTitleFirst,30);
        $I->fillField(NewTourPage::$fieldTitleFirst,$title);
        $I->attachFile(NewTourPage::$btnAddImageFirst,NewTourPage::$imageFirst );
        $I->wait(100);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($url,ManagePage::$urlValue);
    }

    public function createWithClose($name, $url, $title, $description)
    {
        $I = $this;
        $I->click(ManagePage::$btnAddNew);
        $I->comment('create Tour with url is already taken ');
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
        $I->click(NewTourPage::$btnClose);
        $I->waitForElement(ManagePage::$btnLogout,60);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->dontSee($url,ManagePage::$urlValue);
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

    public function clear()
    {
        $I = $this;
        $I->fillField(ManagePage::$nameTour,"");
        $I->fillField(ManagePage::$urlValue,"");
        $I->fillField(NewTourPage::$fieldTitleFirst,"");
        $I->fillField(NewTourPage::$fieldDescriptionFirst,"");
    }

    public function preview($name, $url, $firstTitle, $firstDescription)
    {
        $I = $this;
        $I->comment('Check preview page ');
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->wait(3);
        $I->see($url,ManagePage::$urlValue);
        $I->click(ManagePage::$btnPreview);
        $I->wait(3);
        $I->switchToNextTab();
        $use = new ManagePage();
        $I->amOnUrl($use->returnURL($url));
        $I->wait(5);
        $I->waitForElement(ManagePage::$titlePreview,30);
        $I->waitForText($firstTitle, 30 , ManagePage::$titlePreview);
    }
    
    public function hostPot($name)
    {

    }
}