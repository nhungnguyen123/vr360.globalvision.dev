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
        $I->wait(5);
        $usePage = new NewTourPage();
        $I->waitForElement($usePage->nameField(2), 30);
        $I->waitForElement($usePage->nameField(2),30);
        $I->fillField($usePage->nameField(2),$title);
        $I->fillField($usePage->descriptionField(2),$description);
        $I->attachFile($usePage->imageInput(2),NewTourPage::$imageFirst );
        $I->waitForElement(ManagePage::$checkboxAutoRation, 30);
        $I->waitForElement(ManagePage::$checkBoxSocical, 30);
        $I->click(ManagePage::$checkBoxSocical);
        
        $I->comment('I click Create button');
        $I->click(NewTourPage::$btnCreate);
        $I->wait(150);
        $I->waitForElement(ManagePage::$createSuccessXpath, 30);
        $I->waitForElement(ManagePage::$closeButtonSuccess, 30);
        $I->click(ManagePage::$closeButtonSuccess);
        $I->waitForElement(ManagePage::$buttonClosePopup);
        $I->waitForElement(NewTourPage::$btnClose, 30);
        $I->click(NewTourPage::$btnClose);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->see($url);
        $I->waitForElement(ManagePage::$btnLogout,60);
        $I->click(ManagePage::$btnEditFrirst);
        $I->wait(5);
        $I->seeInField(NewTourPage::$fieldName, $name);
    }

    public function createWithoutAnyScreen($name)
    {
        $I = $this;
        $I->click(ManagePage::$btnAddNew);
        $I->waitForElement(NewTourPage::$fieldName,30);
        $I->comment('Fill Name Text Field');
        $I->wait(1);
        $I->fillField(NewTourPage::$fieldName, $name);
        $I->fillField(NewTourPage::$fieldFriendlyURL, $name);
        $I->wait(1);
        $I->click(NewTourPage::$btnCreate);
        $I->wait(20);
        $I->waitForElement(ManagePage::$buttonCloseDuplicate, 30);
        
    }
    
    public function checkURL($name , $url)
    {
        $I = $this;
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$urlValue,30);
        $I->dontSee($url);
       
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
        $I->click(NewTourPage::$btnAddPano);
        $I->wait(3);
        $usePage = new NewTourPage();
        $I->waitForElement($usePage->nameField(2), 30);
        $I->waitForElement($usePage->nameField(2),30);
        $I->fillField($usePage->nameField(2),$title);
        $I->fillField($usePage->descriptionField(2),$description);
        $I->attachFile($usePage->imageInput(2),NewTourPage::$imageFirst );
        $I->waitForElement(ManagePage::$checkboxAutoRation, 30);
        $I->waitForElement(ManagePage::$checkBoxSocical, 30);
        $I->click(ManagePage::$checkBoxSocical);
        $I->click(NewTourPage::$btnCreate);
        $I->wait(100);
//        $I->waitForElement(NewTourPage::$btnClose,30);
//        $I->click(NewTourPage::$btnClose, 30);
//        $I->fillField(ManagePage::$searchId,$name);
//        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
//        $I->waitForElement(ManagePage::$noResults,30);
    }
    public function checkMissing($name, $url, $title, $description)
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
        $I->wait(5);
        $usePage = new NewTourPage();
        $I->waitForElement($usePage->nameField(2), 30);
        $I->waitForElement($usePage->nameField(2),30);
        $I->fillField($usePage->nameField(2),$title);
        $I->fillField($usePage->descriptionField(2),$description);


        $I->click(NewTourPage::$btnCreate);
        $I->waitForElement(NewTourPage::$missingField, 30);
        $I->see(NewTourPage::$messageMissingField);

        $I->comment('Check missing name');
        $I->fillField(NewTourPage::$fieldName,'');
        $I->attachFile($usePage->imageInput(2),NewTourPage::$imageFirst );
        $I->click(NewTourPage::$btnCreate);
        $I->waitForElement(NewTourPage::$missingField, 30);
        $I->see(NewTourPage::$messageMissingField);

        $I->comment('Check missing url');
        $I->fillField(NewTourPage::$fieldName, $name);
        $I->fillField(NewTourPage::$fieldFriendlyURL,'');
        $I->click(NewTourPage::$btnCreate);
        $I->waitForElement(NewTourPage::$missingField, 30);
        $I->see(NewTourPage::$messageMissingField);

        $I->comment('Creat with missing title for pano');
        $I->fillField(NewTourPage::$fieldFriendlyURL, $url);
        $I->fillField($usePage->nameField(2), '');
        $I->click(NewTourPage::$btnCreate);
        $I->waitForElement(NewTourPage::$missingField, 30);
        $I->see(NewTourPage::$messageMissingField);
    }

    public function createWithClose($name, $url, $title, $description)
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

        $I->comment('I click close button');
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
        $I->wait(150);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$nameEdit);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
        $I->waitForElement(ManagePage::$nameTour,30);
        $I->see($url);
        $I->waitForElement(ManagePage::$btnLogout,60);
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

    public function preview($name, $url, $firstTitle)
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
        $I->wait(10);
//        $I->waitForElement(ManagePage::$titlePreview,30);
//        $I->waitForText($firstTitle, 30 , ManagePage::$titlePreview);
//        $I->waitForElement(ManagePage::$buttonFacePreview, 30);
//        $I->waitForElement(ManagePage::$buttonGGPreview, 30);
//        $I->waitForElement(ManagePage::$buttonTwPreview, 30);
//        $I->waitForElement(ManagePage::$buttonShowImage, 30);
//        $I->click(ManagePage::$buttonShowImage);
//        $I->wait(2);
//        $I->waitForElement(ManagePage::$fistImagePreview, 30);
    }
    
    public function hostPot($name)
    {

    }
}