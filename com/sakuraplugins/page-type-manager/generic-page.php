<?php
/**
* appetit generic page
*/
require_once(dirname(__FILE__) . '/../plugin-core.php');
require_once(dirname(__FILE__) . '/../options/AppetitOptions.php');


class AppetitGenericPage
{
	protected $customPostMeta;
	protected $customPostOptions;
	protected $data;
	protected $menuData;
	protected $sectionsData = array();
	protected $currencySymbol = '$';
	protected $currencyPosition = 0;
	protected $disable_save_to_order = 0;
	protected $allMenuItems = array();

	protected $appetitCustomCSS = '';

	protected $welcomeAbout = 'No welcome text found';
	protected $welcomeLogoId = NULL;
	protected $defaultLogo = APPETIT_FRONT_URI . '/img/logo-default.png';
	protected $welcomeLabel = 'No welcome labe';

	protected $appetitLabels = array();

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

			//wptexturize user text input
			$this->sectionsData[$i]['section_name'] = wptexturize(stripslashes($this->sectionsData[$i]['section_name']));
			
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
					////wptexturize user text input				
					$itemData['item_small_description'] = wptexturize(stripslashes($itemData['item_small_description']));
					$itemData['menu_item_name'] = wptexturize(stripslashes($itemData['menu_item_name']));

					$itemData['inSection'] = $this->sectionsData[$i]['menu_section_id'];
					$itemData['sectionName'] = $this->sectionsData[$i]['section_name'];
					array_push($this->allMenuItems, $itemData);			
				}
			}
		}
	}

	//build labels helper
	protected function buildLabels($menuData) {
		$labelsData = isset($menuData['labelsData']) ? $menuData['labelsData'] : array();		
		$labels_fields = AppetitOptions::getLabelsFields();
		
		foreach ($labels_fields as $object) {	
			$object['currentValue'] = ( isset($labelsData[$object['field']])) ? $labelsData[$object['field']] : $object['default_val'];
			$this->appetitLabels[$object['field']] = wptexturize(stripslashes($object['currentValue']));
		}
	}

	//build menu data
	protected function buildMenuData() {
		$this->menuData = get_option(AppetitCore::APPETIT_MENU_DATA, array());
		$this->sectionsData = isset($this->menuData['sectionsData']) ? $this->menuData['sectionsData'] : $this->sectionsData;
		$this->currencySymbol = (isset($this->menuData['optionsData']) && isset($this->menuData['optionsData']['currencySymbol'])) ? $this->menuData['optionsData']['currencySymbol'] : $this->currencySymbol;
		
		$this->currencyPosition = (isset($this->menuData['optionsData']) && isset($this->menuData['optionsData']['currencyPosition'])) ? $this->menuData['optionsData']['currencyPosition'] : $this->currencyPosition;

		$this->disable_save_to_order = (isset($this->menuData['optionsData']) && isset($this->menuData['optionsData']['disable_save_to_order'])) ? $this->menuData['optionsData']['disable_save_to_order'] : $this->disable_save_to_order;		

		$this->buildImagesSrc();

		$this->welcomeAbout = (isset($this->menuData['welcomeData']) && isset($this->menuData['welcomeData']['welcomeAbout'])) ? $this->menuData['welcomeData']['welcomeAbout'] : $this->welcomeAbout;
		$this->welcomeLogoId = (isset($this->menuData['welcomeData']) && isset($this->menuData['welcomeData']['welcomeLogoId'])) ? $this->menuData['welcomeData']['welcomeLogoId'] : $this->welcomeLogoId;
		$this->welcomeLabel = (isset($this->menuData['welcomeData']) && isset($this->menuData['welcomeData']['welcomeLabel'])) ? $this->menuData['welcomeData']['welcomeLabel'] : $this->welcomeLabel;

		$this->welcomeAbout = wptexturize(stripslashes($this->welcomeAbout));
		$this->welcomeLabel = wptexturize(stripslashes($this->welcomeLabel));

		$this->buildLabels($this->menuData);

		$this->appetitCustomCSS = (isset($this->menuData['optionsData']) && isset($this->menuData['optionsData']['appetitCustomCSS'])) ? trim($this->menuData['optionsData']['appetitCustomCSS']) : $this->appetitCustomCSS;
	}
}
?>