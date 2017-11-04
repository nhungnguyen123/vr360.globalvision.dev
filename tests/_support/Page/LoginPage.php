<?php

namespace Page;
class LoginPage
{
 
    
    public static $usernameField = ['xpath'=>"//input[@name='username']"];
    
    public static $passfield = ['xpath' => "//input[@name='password']"];
    
    public static $btnLogin = ['xpath' =>' //input[@name=\'submit\']'];
    
    public static $logoXpath = ['xpath' =>' //img[@id=\'logo\']'];

    public static $warningUserNameOrPassXpath = ['xpath' => '//div[@class=\'label label-warning\']'];

    public static $warningUsername = ['xpath' =>'//div[@class=\'label label-default\']'];

    public static $warningUserNameOrPassXpathMessage = 'Invalid Username or password';

    public static $warningUsernameMessage = 'Invalid username';
    
    public static $warningInvalidPassMessage = 'Invalid password';

    
}