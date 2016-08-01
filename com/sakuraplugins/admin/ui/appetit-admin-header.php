<?php

/**
* 
*/
class AppetitHeader
{
	public static function render() {
		$appetitInfo = get_plugin_data(APPETIT_FILE, $markup = true, $translate = true );
		?>
		<div class="appetit-admin-header">
			<img src="<?php echo APPETIT_ADMIN_URI . '/img/admin-logo.png'; ?>" alt="logo" />
			<p class="appetitInfo">Version <?php echo $appetitInfo['Version']?></p>
		</div>
		<?php
	}
}

?>