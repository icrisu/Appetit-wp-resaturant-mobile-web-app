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
		add_shortcode('appetit-mixto', array($this, 'appetitMixto'));
	}

	//Appetit luna shortcode
	public function appetitLunaCafe($atts, $content = null) {
		extract(shortcode_atts(array('show_logo' => 'true'), $atts));
		require_once(dirname(__FILE__) . '/../page-type-manager/luna-cafe/luna-cafe-helper.php');
		LunaCafeHelper::buildContent($this->genericPage, array('showLogo' => $show_logo));
		return '';
	}

	//Appetit mixto shortcode
	public function appetitMixto($atts, $content = null) {
		extract(shortcode_atts(array('show_logo' => 'true', 'remove_shadow' => 'true'), $atts));
		require_once(dirname(__FILE__) . '/../page-type-manager/mixto/mixto-helper.php');
		MixtoHelper::buildContent($this->genericPage, array('showLogo' => $show_logo, 'removeShadow' => $remove_shadow));
		return '';
	}	
}
?>