<?php
require_once(dirname(__FILE__) . '/../i-generic-page.php');
require_once(dirname(__FILE__) . '/../generic-page.php');
/**
* appetit mobile admin page
*/
class AppetitMobilePage extends AppetitGenericPage implements iAppetitGenericPage {

	//render admin for the mobile web app
	public function execRenderAdmin() {
		?>
		<p class="appetit_notification"><b>NOTE! </b>You can print the image/QR code below and place on restaurant's tables, when users will scan the code, they will be redirected to the Appetit's mobile web app. If you change the permalink, refresh the page before printing the image. Alternatively you can generate your own QR code image using a third party app/library.</p>
		<div>
			<img src="<?php echo 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=' . urlencode('http://google.com') . '&choe=UTF-8' ;?>" alt="" />
		</div>
		<?php
	}

	//render frontend mobile web app
	public function execRenderFrontend() {
		$this->buildMenuData();
		require_once(dirname(__FILE__) . '/appetit-mobile-header.php');
		require_once(dirname(__FILE__) . '/appetit-mobile-content.php');
		require_once(dirname(__FILE__) . '/appetit-mobile-footer.php');
	}
}
?>