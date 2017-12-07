<?php

use Step\LoginSteps as LoginSteps;
class AdminLoginCest
{
    public function __construct()
    {
        $this->userName = 'designteam';
        $this->pass = '123';
    }

    public function login(LoginSteps $I)
    {
        $I->comment('Do login with Bory');
        $I->login($this->userName, $this->pass);
        
    }
    
    public function checkFace(LoginSteps $I)
    {
        $I->checkFaceSocialIcon();
    }
    public function check(LoginSteps $I)
    {
        $I->checkTwitter();
    }

    public function checkGoogle(LoginSteps $I)
    {
        $I->checkGoogle();
    }
    
    public function checkLogo(LoginSteps $I)
    {
//        $I->checkLogo();
    }
    
    public function LoginWrongValue(LoginSteps $I)
    {
        $I->comment('Do login with Bory');
        $I->LoginWrongValue($this->userName, $this->pass,'name');

        $I->comment('login with missing input Pass');
        $I->LoginWrongValue($this->userName, $this->pass,'pass');

        $I->LoginWrongValue($this->userName, $this->pass,'both');

        $I->LoginWrongValue($this->userName, $this->pass,'wrong');

    }
}
