<?php

namespace Page;
class ManagePage
{

    public static $btnLogout = ['xpath' => '//button[contains(text(), \'Logout\')]'];

    public static $btnAddNew = ['xpath' => './/*[@id=\'bs-example-navbar-collapse-1\']/ul[1]/li/form/button'];
    
    public static $searchId = ['id' => 'task-table-filter'];
    
    public static $urlValue = ['xpath' => '//tr//td[@class=\'vtour-url\']'];
    
    public static $nameTour = ['xpath' =>'(//tr//td[@class=\'vtour-name\'])'];

    public static $btnEmbedfirst = ['xpath' => '//tr//td[@class=\'controls\']//button[@class=\'btn btn-default btn-sm embedCode\']'];

    public static $btnEditFrirst = ['xpath' =>'//tr//td[@class=\'controls\']//button[@class=\'btn btn-primary btn-sm editTour\'] '];

    public static $btnHostPot = ['xpath' => '//tr//td[@class=\'controls\']//button[@class=\'btn btn-primary btn-sm editTourHotspot\']'];

    public static $btnPreview = ['xpath' => './/*[@id=\'vtour-40\']/td[7]/a'];

    public static $btnRemove = ['xpath' =>'.//*[@id=\'vtour-40\']/td[7]/button[4]'];

    public static $pano = ['xpath' =>' //tr//td[@class=\'controls\']//div/span[1]'];

    public static $hostpot = ['xpath' =>'//tr//td[@class=\'controls\']//div/span[2]'];
    
    //edit page 
    public static $contentEditXpath = ['xpath' => '(//h4[@id=\'myModalLabel\'])[2]'];


    //preview page
     public static $titlePreview = ['xpath' => '//div[@id=\'pano\']/div/div[1]/div[2]/div[10]//div//div/div/div'];

    //content
    public static $contentEdit = 'Edit tour';

    //hotpot
    public static $addHotsPotContent = 'Add hotspot';

    public static $addHotsPotXpath = ['id' => 'myModalLabel'];

    public function returnURL($url)
    {
        $urlShow = 'http://dev.globalvision.ch/'.$url;
        return $urlShow;
    }

    


    
}