<?php
require_once(dirname(__FILE__) . '/i-generic-page.php');
require_once(dirname(__FILE__) . '/generic-page.php');
/**
* appetit mobile admin page
*/
class AppetitMobilePage extends AppetitGenericPage implements iAppetitGenericPage {

	function __construct($customPostMeta, $customPostOptions) {
		parent::__construct($customPostMeta, $customPostOptions);
	}

	public function execRender() {
		?>
		<div>
			<img src="<?php echo 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=' . urlencode('http://google.com') . '&choe=UTF-8' ;?>" alt="" />
		</div>
		<?php
	}
}
?>