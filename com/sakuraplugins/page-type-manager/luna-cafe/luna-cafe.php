<?php
require_once(dirname(__FILE__) . '/../i-generic-page.php');
require_once(dirname(__FILE__) . '/../generic-page.php');
/**
* appetit mobile admin page
*/
class LunaCafePage extends AppetitGenericPage implements iAppetitGenericPage {

	//render admin for the mobile web app
	public function execRenderAdmin() {
		?>
		<h4>Luna Cafe - page type</h4>
		<p class="appetit_notification"><b>NOTE! </b>You can also use the shortcode below to add this menu within pages and posts.</p>
		<p>[appetit-luna-cafe]</p>
		<div>
		</div>
		<?php
	}

	//render frontend mobile web app
	public function execRenderFrontend() {
		$this->buildMenuData();
		require_once(dirname(__FILE__) . '/header.php');
		require_once(dirname(__FILE__) . '/luna-cafe-helper.php');
		LunaCafeHelper::buildContent($this);
		require_once(dirname(__FILE__) . '/footer.php');
	}
}

?>