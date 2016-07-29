<?php
require_once(dirname(__FILE__) . '/../i-generic-page.php');
require_once(dirname(__FILE__) . '/../generic-page.php');
/**
* appetit luna page
*/
class LunaCafePage extends AppetitGenericPage implements iAppetitGenericPage {

	//render admin for the mobile web app
	public function execRenderAdmin() {
		?>
		<h4 class="appetit-cpt-admin-page-title">Luna Cafe - page type</h4>
		<div class="hr-line"></div>
		<p class="appetit_notification"><b>NOTE! </b>You can also use the shortcode below to embed this menu type within pages and posts.</p>
		<p class="shortcode-info-ui"><span class="shortcode-display-info">[appetit-luna-cafe]</span><span class="shortcode-info-label">Embed with the logo</span></p>
		<p class="shortcode-info-ui"><span class="shortcode-display-info">[appetit-luna-cafe show_logo="false"]</span><span class="shortcode-info-label">Embed without the logo</span></p>
		<div class="appetit-admin-space-15"></div>
		<?php
	}

	//render frontend mobile web app
	public function execRenderFrontend() {
		$this->buildMenuData();
		require_once(dirname(__FILE__) . '/header.php');
		require_once(dirname(__FILE__) . '/luna-cafe-helper.php');
		LunaCafeHelper::buildContent($this, array('showLogo' => 'true'));
		require_once(dirname(__FILE__) . '/footer.php');
	}
}

?>