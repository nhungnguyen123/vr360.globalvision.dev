<?php
/**
 * Created by PhpStorm.
 * User: nhung
 * Date: 04/11/2017
 * Time: 07:24
 */

namespace Page;


class NewTourPage
{

    public static $fieldName  = ['xpath' => './/*[@id=\'name\']'];

    public static $fieldFriendlyURL = ['xpath' => '//input[@id=\'alias\']'];

    public static $checkAutoRation = ['xpath' => '//input[@id=\'tour_rotation\']'];

    public static $checkShowMedia = ['xpath' => '//input[@id=\'tour_social\']' ];

    public static $fieldTitleFirst = ['xpath' => '(//div[@id=\'panoWrap\']/div[2]/input)[2]'];

    public static $fieldDescriptionFirst = ['xpath' => '(//div[@id=\'panoWrap\']/div[3]/input)[2]'];

    public static $btnAddImageFirst = ['xpath' => '(//div[@id=\'panoWrap\']/div[1]/input)[2]'];

    public static $btnClose = ['xpath' => '//div[@id=\'vrTour\']/div/div/div[3]/button'];

    public static $btnRemovePano = ['xpath' => '//div[@id=\'tour-panos\']/div/div[1]/button'];

    public static $btnCreate = ['xpath' => '//button[@id=\'createTour\']'];

    public static $btnAddPano = ['xpath' => '//button[@id=\'addPano\']'];
    
    //image 
    public static $imageFirst = '1.jpg';


}