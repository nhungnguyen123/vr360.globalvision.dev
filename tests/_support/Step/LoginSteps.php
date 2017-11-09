<?php
namespace Step;
use Page\LoginPage as LoginPage;
use Page\ManagePage as ManagePage;
class LoginSteps extends ManageSteps
{

    public function login($username, $pass)
    {
        $I = $this;
        $I->amOnPage('');
        $I->comment('Fill Username Text Field');
        $I->fillField(LoginPage::$usernameField, $username);
        $I->comment('Fill Password Text Field');
        $I->fillField(LoginPage::$passfield, $pass);
        $I->comment('I click Login button');
        $I->click(LoginPage::$btnLogin);
        $I->waitForElement(ManagePage::$btnLogout,30);
        $I->comment('I see Administrator Control Panel');
    }

    public function LoginWrongValue($username, $pass,$function)
    {
        $I = $this;
        $I->clearField();
        switch ($function)
        {
            case 'name':
                $I->comment(' Missing inpt name');
                $I->comment('Fill Password Text Field');
                $I->fillField(LoginPage::$passfield, $pass);
                $I->click(LoginPage::$btnLogin);
                $I->waitForElement(LoginPage::$warningUsername,30);
                $I->waitForText(LoginPage::$warningUsernameMessage,30,LoginPage::$warningUsername);
                break;
            case 'pass':
                $I->comment(' Missing inpt pass');
                $I->comment('Fill Password Text Field');
                $I->fillField(LoginPage::$usernameField, $username);
                $I->click(LoginPage::$btnLogin);
                $I->waitForElement(LoginPage::$warningUsername,30);
                $I->waitForText(LoginPage::$warningInvalidPassMessage,30,LoginPage::$warningUsername);
                break;
            case 'both':
                $I->comment(' Missing input username and pass');
                $I->click(LoginPage::$btnLogin);
                $I->waitForElement(LoginPage::$warningUsername,30);
                $I->waitForText(LoginPage::$warningUsernameMessage,30,LoginPage::$warningUsername);
                break;
            case 'wrong':
                $I->comment('Wrong Pass ');
                $I->comment('Fill Username Text Field');
                $I->fillField(LoginPage::$usernameField, $username.'Edit');
                $I->comment('Fill Password Text Field');
                $I->fillField(LoginPage::$passfield, $pass);
                $I->click(LoginPage::$btnLogin);
                $I->waitForElement(LoginPage::$warningUserNameOrPassXpath,30);
                $I->waitForText(LoginPage::$warningUserNameOrPassXpathMessage,30,LoginPage::$warningUserNameOrPassXpath);
                break;
            default:
                break;
        }

    }


    public function checkFaceSocialIcon()
    {
        $I = $this;
        $I->comment('Check facebook icon');
        $I->amOnPage('');
        $I->click(LoginPage::$faceIcon);
        $I->switchToNextTab();
        $I->waitForElement(LoginPage::$facePage,30);
        $I->amOnUrl(LoginPage::$faceURL);
    }
    
    public function checkTwitter()
    {

        $I = $this;
        $I->comment('Check tweet ');
        $I->amOnPage('');
        $I->click(LoginPage::$tweeticon);
        $I->switchToNextTab();
        $I->amOnUrl(LoginPage::$twitterURL);
    }
    
    public function checkGoogle()
    {
        $I  = $this;
        $I->comment('Check Google + ');
        $I->amOnPage('');
        $I->click(LoginPage::$googleIcon);
        $I->switchToNextTab();
        $I->amOnUrl(LoginPage::$googleURL);
    }

    public function checkLogo()
    {
        $I = $this;
        $I->comment('Check facebook icon');
        $I->amOnPage('');
        $I->click(LoginPage::$logoId);
        $I->switchToNextTab();
        $I->wait(5);
        $I->amOnUrl(LoginPage::$pageMain);
    }
    
    public function clearField()
    {
        $I  = $this;
        $I->amOnPage('');
        $I->fillField(LoginPage::$usernameField, '');
        $I->fillField(LoginPage::$passfield, '');
    }
}