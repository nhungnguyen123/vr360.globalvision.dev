<?php

namespace Page;

class ManagePage
{
	public static $btnLogout = ['id' => 'logout'];

	public static $btnAddNew = ['id' => 'addTour'];

	public static $searchId = ['id' => 'task-table-filter'];

	public static $buttonReset = ['xpath' => '//button[@id=\'reset-search\']'];

	public static $urlValue = ['xpath' => '//tr//td[@class=\'vtour-url\']'];

	public static $nameTour = ['xpath' => '(//tr//td[@class=\'vtour-name\'])'];

	public static $noResults = ['xpath' => '//div[@class=\'alert alert-warning\']'];

	public static $createSuccessXpath = ['xpath' => '//div[@class=\'alert alert-info\']'];

	public static $closeButtonSuccess = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \' ajax-close \')]'];

	public static $messageSaveSuccess = 'Tour is created';

	public static $btnEmbedfirst = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'embedCode \')]'];

	public static $btnEditFrirst = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'editTour \')]'];

	public static $btnHostPot = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'editTourHotspot \')]'];

	public static $btnPreview = ['xpath' => '//a[contains(concat(\' \', @class, \' \'), \'previewTour \')]'];

	public static $btnRemove = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'removeTour \')]'];


	public static $checkBoxSocial = ['xpath' => '//input[@id=\'tour_social\']'];

	public static $checkboxAutoRation = ['xpath' => '//input[@id=\'tour_rotation\']'];

	//edit page
	public static $contentEditXpath = ['xpath' => '(//h4[@id=\'myModalLabel\'])[2]'];

	//profile view
	public static $profileImg = ['xpath' => '//img[@id=\'avatar\']'];

	//content
	public static $contentEdit = 'Edit tour';

	//already

    /**
     * @var array
     */
    public static $btnClose = ['id' => 'modal-close'];

	//hotpot
	public static $addHotsPotXpath = ['id' => 'myModalLabel'];

	/**
	 * @param   string  $url
	 *
	 * @return  string
	 */
	public function returnURL($url)
	{
		$urlShow = 'http://dev.globalvision.ch/' . $url;
		
		return $urlShow;
	}
}