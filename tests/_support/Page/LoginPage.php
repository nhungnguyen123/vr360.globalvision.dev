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

    public static $faceIcon = ['xpath' => '//i[@id=\'social-fb\']'];

    public static $facePage = ['xpath' =>'//i[@class=\'fb_logo img sp_ex0C8BIsLat sx_7401de\']'];
    
    public static $faceURL = 'https://www.facebook.com/globalvision360/';

    public static $tweeticon  = ['xpath' => '//i[@id=\'social-tw\']'];

    public static $twitterURL = 'https://twitter.com/GlobalVision360';

    public static $googleIcon = ['xpath' =>'//i[@id=\'social-gp\']'];
    
    public static $googleURL = 'https://plus.google.com/+GlobalVisionSwitzerland';


    
    
}