<?php

namespace Page;
class ManagePage
{

    public static $btnLogout = ['xpath' => '//button[contains(text(), \'Logout\')]'];

    public static $btnAddNew = ['xpath' => '//button[contains(text(), \'Add new\')]'];
    
    public static $searchId = ['id' => 'task-table-filter'];
    
    public static $urlValue = ['xpath' => '//tr//td[@class=\'vtour-url\']'];
    
    public static $nameTour = ['xpath' =>'(//tr//td[@class=\'vtour-name\'])'];

    public static $btnEmbedfirst = ['xpath' => '//tr//td[@class=\'controls\']//button[@class=\'btn btn-default btn-sm embedCode\']'];

    public static $btnEditFrirst = ['xpath' =>'//tr//td[@class=\'controls\']//button[@class=\'btn btn-primary btn-sm editTour\'] '];

    public static $btnHostPot = ['xpath' => '//tr//td[@class=\'controls\']//button[@class=\'btn btn-primary btn-sm editTourHotspot\']'];

    public static $btnPreview = ['xpath' => '//tr//td[@class=\'controls\']//button[@class=\'btn btn-info btn-sm previewTour\']'];

    public static $btnRemove = ['xpath' =>'//tr//td[@class=\'controls\']//button[@class=\'btn btn-danger btn-sm removeTour\']'];

    public static $pano = ['xpath' =>' //tr//td[@class=\'controls\']//div/span[1]'];

    public static $hostpot = ['xpath' =>'//tr//td[@class=\'controls\']//div/span[2]'];
    
    //edit page 
    public static $contentEditXpath = ['xpath' => '(//h4[@id=\'myModalLabel\'])[2]'];
    
    //content
    public static $contentEdit = 'Edit tour';

    


    
}