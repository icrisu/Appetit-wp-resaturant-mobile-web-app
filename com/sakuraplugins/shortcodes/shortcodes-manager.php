<?php
require_once(dirname(__FILE__) . '/../page-type-manager/generic-page.php');
/**
* shortocdes manager
*/
class ShortcodesManager
{
	private $genericPage;

	function __construct() {
		$this->genericPage = new AppetitGenericPage(null, null);
		$this->genericPage->buildMenuData();
	}

	//shortcodes
	public function registerShortcodes() {
		add_shortcode('appetit-luna-cafe', array($this, 'appetitLunaCafe'));
	}

	//Appetit luna shortcode
	public function appetitLunaCafe($atts, $content = null) {
		require_once(dirname(__FILE__) . '/../page-type-manager/luna-cafe/luna-cafe-helper.php');
		LunaCafeHelper::buildContent($this->genericPage);
		return '';
	}
}
?>