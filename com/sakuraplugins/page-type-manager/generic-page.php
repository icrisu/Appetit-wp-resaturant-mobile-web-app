<?php
/**
* appetit generic page
*/
require_once(dirname(__FILE__) . '/../plugin-core.php');

class AppetitGenericPage
{
	protected $customPostMeta;
	protected $customPostOptions;
	protected $data;
	protected $menuData;
	protected $sectionsData = array();
	protected $currencySymbol = '$';
	protected $currencyPosition = 0;
	protected $allMenuItems = array();

	protected $welcomeAbout = 'No welcome text found';
	protected $welcomeLogoId = NULL;
	protected $defaultLogo = APPETIT_FRONT_URI . '/img/logo-default.png';
	protected $welcomeLabel = 'No welcome labe';

	function __construct($customPostMeta, $customPostOptions, $data = null) {
		$this->customPostMeta = $customPostMeta;
		$this->customPostOptions = $customPostOptions;
		$this->data = $data;
	}

	protected function buildImagesSrc() {
		for ($i=0; $i < sizeof($this->sectionsData); $i++) {
			if (isset($this->sectionsData[$i]['section_img_id']) && $this->sectionsData[$i]['section_img_id'] != '') {
				$this->sectionsData[$i]['section_image_src'] = wp_get_attachment_image_url($this->sectionsData[$i]['section_img_id']);
				$this->sectionsData[$i]['section_image_srcset'] = wp_get_attachment_image_srcset($this->sectionsData[$i]['section_img_id']);
			} else {
				$this->sectionsData[$i]['section_image_src'] = '';
				$this->sectionsData[$i]['section_image_srcset'] = '';
			}
			
			if (isset($this->sectionsData[$i]['section_items'])) {
				for ($k=0; $k < sizeof($this->sectionsData[$i]['section_items']); $k++) { 

					if (isset($this->sectionsData[$i]['section_items'][$k]['menu_img_id']) && $this->sectionsData[$i]['section_items'][$k]['menu_img_id'] != '') {
						$this->sectionsData[$i]['section_items'][$k]['menu_img_src'] = wp_get_attachment_image_url($this->sectionsData[$i]['section_items'][$k]['menu_img_id']);
						$this->sectionsData[$i]['section_items'][$k]['menu_img_srcset'] = wp_get_attachment_image_srcset($this->sectionsData[$i]['section_items'][$k]['menu_img_id']);						
					} else {
						$this->sectionsData[$i]['section_items'][$k]['menu_img_src'] = '';
						$this->sectionsData[$i]['section_items'][$k]['menu_img_srcset'] = '';
					}
					
					$itemData = $this->sectionsData[$i]['section_items'][$k];
					$itemData['inSection'] = $this->sectionsData[$i]['menu_section_id'];
					array_push($this->allMenuItems, $itemData);					
				}
			}
		}
	}

	//build menu data
	protected function buildMenuData() {
		$this->menuData = get_option(AppetitCore::APPETIT_MENU_DATA, array());
		$this->sectionsData = isset($this->menuData['sectionsData']) ? $this->menuData['sectionsData'] : $this->sectionsData;
		$this->currencySymbol = (isset($this->menuData['optionsData']) && isset($this->menuData['optionsData']['currencySymbol'])) ? $this->menuData['optionsData']['currencySymbol'] : $this->currencySymbol;
		$this->currencyPosition = (isset($this->menuData['optionsData']) && isset($this->menuData['optionsData']['currencyPosition'])) ? $this->menuData['optionsData']['currencyPosition'] : $this->currencySymbol;

		$this->buildImagesSrc();

		$this->welcomeAbout = (isset($this->menuData['welcomeData']) && isset($this->menuData['welcomeData']['welcomeAbout'])) ? $this->menuData['welcomeData']['welcomeAbout'] : $this->welcomeAbout;
		$this->welcomeLogoId = (isset($this->menuData['welcomeData']) && isset($this->menuData['welcomeData']['welcomeLogoId'])) ? $this->menuData['welcomeData']['welcomeLogoId'] : $this->welcomeLogoId;
		$this->welcomeLabel = (isset($this->menuData['welcomeData']) && isset($this->menuData['welcomeData']['welcomeLabel'])) ? $this->menuData['welcomeData']['welcomeLabel'] : $this->welcomeLabel;
	}
}
?>